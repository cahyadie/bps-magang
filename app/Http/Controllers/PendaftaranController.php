<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Magang;
use App\Models\RefAgama;      // Tambahan
use App\Models\RefProvinsi;   // Tambahan
use App\Models\RefKabupaten;  // Tambahan
use App\Mail\PendaftaranStatusMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PendaftaranController extends Controller
{
    /**
     * Menampilkan daftar pendaftaran dengan filter dan pagination.
     */
    public function index(Request $request)
    {
        $query = Pendaftaran::query();

        // Jika user yang login BUKAN Admin, maka filter hanya data miliknya saja (user_id).
        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        // 1. Ambil Data Referensi untuk Filter UI
        $availableYears = $this->getAvailableYears($query);
        $kampusList = $this->getKampusList();

        // 2. Terapkan Filter pada Query
        $selectedYear = $request->input('year', 'all');
        $this->applyFilters($query, $request);

        // 3. Ambil Data (Pagination)
        $pendaftarans = $query->latest()->paginate(10)->appends($request->all());

        return view('daftar.index', compact('pendaftarans', 'kampusList', 'availableYears', 'selectedYear'));
    }

    /**
     * Menampilkan Form Create dengan Data Dropdown
     */
    public function create()
    {
        // [MODIFIKASI] Ambil data master untuk dropdown
        $agamas = RefAgama::all();
        $provinsis = RefProvinsi::all();

        return view('daftar.create', compact('agamas', 'provinsis'));
    }

    /**
     * [BARU] AJAX Request untuk mengambil data Kabupaten berdasarkan Provinsi
     */
    public function getKabupaten(Request $request)
    {
        // Kita mengambil 'nama' sebagai key dan value karena database pendaftaran menyimpan string (nama)
        $kabupatens = RefKabupaten::where('provinsi_id', $request->provinsi_id)
                                  ->pluck('nama', 'nama'); 
        
        return response()->json($kabupatens);
    }

    /**
     * Menyimpan pendaftaran baru.
     */
    public function store(Request $request)
    {
        // 1. Validasi (Termasuk agama, provinsi, kabupaten, alamat)
        $validated = $this->validatePendaftaran($request);

        // 2. Upload File
        $validated['surat_permohonan'] = $this->handleUpload($request, 'surat_permohonan', 'surat_permohonan');
        $validated['surat_kampus'] = $this->handleUpload($request, 'surat_kampus', 'surat_kampus');
        $validated['pas_foto'] = $this->handleUpload($request, 'pas_foto', 'foto_pendaftar');

        // 3. Set Default Data
        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        // 4. Simpan ke Database
        Pendaftaran::create($validated);

        // 5. Output/Feedback
        return redirect()->route('daftar.index')
            ->with('success', 'Pendaftaran berhasil dikirim! Silakan tunggu konfirmasi dari Admin.');
    }

    public function show(Pendaftaran $pendaftaran)
    {
        $this->authorizeAccess($pendaftaran);
        return view('daftar.show', compact('pendaftaran'));
    }

    public function edit(Pendaftaran $pendaftaran)
    {
        $this->authorizeOwner($pendaftaran);

        if ($pendaftaran->status === 'approved') {
            return redirect()->route('daftar.index')
                ->withErrors('Pendaftaran yang sudah disetujui tidak dapat diubah.');
        }

        // [MODIFIKASI] Kirim juga data master agar dropdown di form edit berfungsi
        $agamas = RefAgama::all();
        $provinsis = RefProvinsi::all();

        return view('daftar.edit', compact('pendaftaran', 'agamas', 'provinsis'));
    }

    /**
     * Update data pendaftaran.
     */
    public function update(Request $request, Pendaftaran $pendaftaran)
    {
        $this->authorizeOwner($pendaftaran);

        if ($pendaftaran->status === 'approved') {
            return redirect()->route('daftar.index')
                ->withErrors('Pendaftaran yang sudah disetujui tidak dapat diubah.');
        }

        // 1. Validasi (Termasuk agama, provinsi, kabupaten, alamat)
        $validated = $this->validatePendaftaran($request, true);

        // 2. Handle File Uploads (Hapus lama jika ada baru)
        if ($path = $this->handleUpload($request, 'surat_permohonan', 'surat_permohonan', $pendaftaran->surat_permohonan)) {
            $validated['surat_permohonan'] = $path;
        }
        if ($path = $this->handleUpload($request, 'surat_kampus', 'surat_kampus', $pendaftaran->surat_kampus)) {
            $validated['surat_kampus'] = $path;
        }
        if ($path = $this->handleUpload($request, 'pas_foto', 'foto_pendaftar', $pendaftaran->pas_foto)) {
            $validated['pas_foto'] = $path;
        }

        // 3. Reset Status saat update ulang
        $validated['status'] = 'pending';
        $validated['remarks'] = null;
        $validated['konfirmasi_at'] = null;

        $pendaftaran->update($validated);

        return redirect()->route('daftar.index')
            ->with('success', 'Pendaftaran berhasil diperbarui dan dikirim ulang.');
    }

    /**
     * Logic Approval/Rejection oleh Admin.
     */
    public function updateStatus(Request $request, Pendaftaran $pendaftaran)
    {
        if (!auth()->user()->isAdmin())
            abort(403);

        $request->validate([
            'status' => 'required|in:approved,rejected,conditional',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $status = $request->status;
        $remarks = $request->remarks;

        // Validasi Remarks Wajib untuk Rejected/Conditional
        if (in_array($status, ['rejected', 'conditional']) && empty($remarks)) {
            return back()->withInput()->withErrors(['remarks' => 'Catatan wajib diisi untuk status ini.']);
        }

        if ($status === 'approved' && $pendaftaran->status === 'approved') {
            return redirect()->route('daftar.index')->with('success', 'Pendaftar ini sudah disetujui sebelumnya.');
        }

        // --- DATABASE TRANSACTION MULAI ---
        try {
            DB::transaction(function () use ($pendaftaran, $status, $remarks) {

                if ($status === 'approved') {
                    // 1. Copy Foto ke folder magang
                    $sourcePath = $pendaftaran->pas_foto;

                    if (!Storage::disk('public')->exists($sourcePath)) {
                        $newPath = null;
                    } else {
                        $fileName = Str::afterLast($sourcePath, '/');
                        $newPath = 'foto_magang/' . $fileName;
                        Storage::disk('public')->copy($sourcePath, $newPath);
                    }

                    // 2. Buat Data Magang (Master Data)
                    Magang::create([
                        'user_id' => $pendaftaran->user_id,
                        'nama' => $pendaftaran->nama_pendaftar,
                        'asal_kampus' => $pendaftaran->asal_kampus,
                        'prodi' => $pendaftaran->prodi,
                        'tanggal_mulai' => $pendaftaran->tanggal_mulai,
                        'tanggal_selesai' => $pendaftaran->tanggal_selesai,
                        'foto' => $newPath,
                        
                        // [OPSIONAL] Pastikan tabel magangs sudah punya kolom ini jika ingin dicopy
                        // 'agama' => $pendaftaran->agama,
                        // 'provinsi' => $pendaftaran->provinsi,
                        // 'kabupaten' => $pendaftaran->kabupaten, // Tambahan
                        // 'alamat' => $pendaftaran->alamat,
                        // 'email' => $pendaftaran->email,
                    ]);
                }

                // 3. Update Status Pendaftaran
                $pendaftaran->update([
                    'status' => $status,
                    'remarks' => $remarks
                ]);

                // 4. Kirim Email Notifikasi
                try {
                    Mail::to($pendaftaran->email)->send(new PendaftaranStatusMail($pendaftaran));
                } catch (\Exception $e) {
                    // Log::error('Gagal kirim email: ' . $e->getMessage());
                }
            });

            $msg = $status === 'approved' ? 'disetujui' : ($status === 'rejected' ? 'ditolak' : 'diberi syarat');
            return redirect()->route('daftar.index')->with('success', "Pendaftaran berhasil $msg.");

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function downloadFile(Pendaftaran $pendaftaran, $field)
    {
        $this->authorizeAccess($pendaftaran);

        if (!in_array($field, ['surat_permohonan', 'surat_kampus', 'pas_foto'])) {
            abort(404);
        }

        $path = $pendaftaran->$field;

        if (!$path || !Storage::disk('public')->exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($path);
    }

    public function konfirmasiKehadiran(Pendaftaran $pendaftaran)
    {
        $message = '';
        $isError = false;

        if ($pendaftaran->status !== 'approved') {
            $message = 'Konfirmasi gagal. Status pendaftaran Anda belum disetujui.';
            $isError = true;
        } elseif ($pendaftaran->konfirmasi_at) {
            $message = 'Anda sudah melakukan konfirmasi pada ' . Carbon::parse($pendaftaran->konfirmasi_at)->format('d F Y, H:i') . ' WIB.';
        } else {
            $pendaftaran->update(['konfirmasi_at' => now()]);
            $message = 'Terima kasih! Kehadiran Anda telah berhasil dikonfirmasi.';
        }

        return view('daftar.konfirmasi', compact('pendaftaran', 'message', 'isError'));
    }

    public function exportPdf($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        $pdf = Pdf::loadView('daftar.pdf', compact('pendaftaran'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('Bukti-Pendaftaran-' . $pendaftaran->nama_pendaftar . '.pdf');
    }

    /*
    |--------------------------------------------------------------------------
    | Private Helper Methods
    |--------------------------------------------------------------------------
    */

    private function validatePendaftaran(Request $request, $isUpdate = false)
    {
        $rules = [
            'nama_pendaftar' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'asal_kampus' => 'required|string|max:255',
            'prodi' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            
            // [MODIFIKASI] Validasi Baru (Agama, Provinsi, Kabupaten, Alamat)
            'agama' => 'required|string|max:50',
            'provinsi' => 'required|string|max:100',
            'kabupaten' => 'required|string|max:100', // Wajib diisi
            'alamat' => 'required|string|max:500',
        ];

        $fileRule = $isUpdate ? 'nullable' : 'required';

        $rules['surat_permohonan'] = "$fileRule|file|mimes:pdf|max:2048";
        $rules['surat_kampus'] = "$fileRule|file|mimes:pdf|max:2048";
        $rules['pas_foto'] = "$fileRule|file|image|mimes:jpeg,png,jpg|max:2048";

        return $request->validate($rules);
    }

    private function handleUpload(Request $request, $key, $folder, $oldPath = null)
    {
        if ($request->hasFile($key)) {
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
            return $request->file($key)->store($folder, 'public');
        }
        return null;
    }

    private function authorizeAccess(Pendaftaran $pendaftaran)
    {
        if (!auth()->user()->isAdmin() && $pendaftaran->user_id !== auth()->id()) {
            abort(403, 'AKSES DITOLAK');
        }
    }

    private function authorizeOwner(Pendaftaran $pendaftaran)
    {
        if ($pendaftaran->user_id !== auth()->id()) {
            abort(403, 'AKSES DITOLAK');
        }
    }

    private function getAvailableYears($baseQuery)
    {
        return (clone $baseQuery)
            ->selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->filter();
    }

    private function getKampusList()
    {
        return Pendaftaran::select('asal_kampus')
            ->distinct()
            ->orderBy('asal_kampus', 'asc')
            ->pluck('asal_kampus')
            ->filter();
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->filled('search')) {
            $query->where('nama_pendaftar', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('kampus')) {
            $query->where('asal_kampus', $request->kampus);
        }
        if ($request->year !== 'all' && $request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }
    }
}