<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Magang;
use Illuminate\Http\Request; // <-- Pastikan ini ada
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\PendaftaranStatusMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // <-- TAMBAHKAN IMPORT INI

class PendaftaranController extends Controller
{
    /**
     * Menampilkan halaman tabel pendaftaran dengan filter tahun.
     */
    public function index(Request $request) // <-- Tambahkan Request $request
    {
        $selectedYear = $request->input('year', 'all'); // Default 'all'
        $user = auth()->user();
        
        // 1. Buat query dasar (Admin melihat semua, User melihat miliknya)
        if ($user->isAdmin()) {
            $query = Pendaftaran::query();
        } else {
            $query = Pendaftaran::where('user_id', $user->id);
        }

        // 2. Ambil daftar tahun yang tersedia (berdasarkan query dasar)
        // Kita clone query agar tidak terpengaruh filter
        $availableYears = (clone $query) 
                             ->select(DB::raw('YEAR(created_at) as year'))
                             ->distinct()
                             ->whereNotNull('created_at')
                             ->orderBy('year', 'desc')
                             ->pluck('year');

        // 3. Terapkan filter tahun jika dipilih
        if ($selectedYear != 'all') {
            // Filter berdasarkan tanggal pendaftaran dibuat (created_at)
            $query->whereYear('created_at', $selectedYear);
        }

        // 4. Ambil hasil akhir
        $pendaftarans = $query->latest()->get(); // 'latest()' menggantikan orderBy('created_at', 'desc')

        // 5. Kirim data ke view
        return view('daftar.index', [
            'pendaftarans' => $pendaftarans,
            'availableYears' => $availableYears,
            'selectedYear' => $selectedYear,
        ]);
    }

    /**
     * Menampilkan form untuk membuat pendaftaran baru.
     */
    public function create()
    {
        return view('daftar.create');
    }

    /**
     * Menyimpan pendaftaran baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pendaftar' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'surat_permohonan' => 'required|file|mimes:pdf|max:2048',
            'surat_kampus' => 'required|file|mimes:pdf|max:2048',
            'pas_foto' => 'required|file|image|mimes:jpeg,png,jpg|max:1024',
            'asal_kampus' => 'required|string|max:255',
            'prodi' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $validated['surat_permohonan'] = $request->file('surat_permohonan')->store('surat_permohonan', 'public');
        $validated['surat_kampus'] = $request->file('surat_kampus')->store('surat_kampus', 'public');
        $validated['pas_foto'] = $request->file('pas_foto')->store('pas_foto', 'public');
        
        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending'; 

        Pendaftaran::create($validated);

        return redirect()->route('daftar.index')->with('success', 'Pendaftaran berhasil dikirim! Silakan tunggu konfirmasi dari Admin.');
    }

    /**
     * Menampilkan halaman detail pendaftaran.
     */
    public function show(Pendaftaran $pendaftaran)
    {
        if (!auth()->user()->isAdmin() && $pendaftaran->user_id != auth()->id()) {
            abort(403, 'AKSES DITOLAK');
        }
        
        return view('daftar.show', compact('pendaftaran'));
    }

    /**
     * Menampilkan form edit untuk pendaftar
     */
    public function edit(Pendaftaran $pendaftaran)
    {
        if ($pendaftaran->user_id != auth()->id()) {
            abort(403, 'AKSES DITOLAK. Anda hanya dapat mengedit pendaftaran Anda sendiri.');
        }

        if ($pendaftaran->status == 'approved') {
             return redirect()->route('daftar.index')->withErrors('Pendaftaran yang sudah disetujui tidak dapat diubah.');
        }

        return view('daftar.edit', compact('pendaftaran'));
    }

    /**
     * Memperbarui data pendaftaran
     */
    public function update(Request $request, Pendaftaran $pendaftaran)
    {
        if ($pendaftaran->user_id != auth()->id()) {
            abort(403, 'AKSES DITOLAK.');
        }
        
        if ($pendaftaran->status == 'approved') {
             return redirect()->route('daftar.index')->withErrors('Pendaftaran yang sudah disetujui tidak dapat diubah.');
        }

        $validated = $request->validate([
            'nama_pendaftar' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'asal_kampus' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'surat_permohonan' => 'nullable|file|mimes:pdf|max:2048',
            'surat_kampus' => 'nullable|file|mimes:pdf|max:2048',
            'pas_foto' => 'nullable|file|image|mimes:jpeg,png,jpg|max:1024',
        ]);

        if ($request->hasFile('surat_permohonan')) {
            Storage::disk('public')->delete($pendaftaran->surat_permohonan);
            $validated['surat_permohonan'] = $request->file('surat_permohonan')->store('surat_permohonan', 'public');
        }

        if ($request->hasFile('surat_kampus')) {
            Storage::disk('public')->delete($pendaftaran->surat_kampus);
            $validated['surat_kampus'] = $request->file('surat_kampus')->store('surat_kampus', 'public');
        }
        
        if ($request->hasFile('pas_foto')) {
            Storage::disk('public')->delete($pendaftaran->pas_foto);
            $validated['pas_foto'] = $request->file('pas_foto')->store('pas_foto', 'public');
        }

        $validated['status'] = 'pending';
        $validated['remarks'] = null;
        $validated['konfirmasi_at'] = null;

        $pendaftaran->update($validated);

        return redirect()->route('daftar.index')->with('success', 'Pendaftaran Anda berhasil diperbarui dan telah dikirim ulang untuk direview.');
    }


    /**
     * Memproses tindakan Admin (Approve, Reject, Conditional).
     */
    public function updateStatus(Request $request, Pendaftaran $pendaftaran)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'HANYA ADMIN YANG DIIZINKAN.');
        }

        $request->validate([
            'status' => 'required|string|in:approved,rejected,conditional',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $status = $request->input('status');
        $remarks = $request->input('remarks');

        if (in_array($status, ['rejected', 'conditional']) && empty($remarks)) {
            return back()->withInput()->withErrors(['remarks' => 'Catatan wajib diisi untuk status Ditolak atau Bersyarat.']);
        }

        // --- LOGIKA UTAMA ---
        if ($status == 'approved') {
            if ($pendaftaran->status == 'approved') {
                 return redirect()->route('daftar.index')->with('success', 'Pendaftar ini sudah disetujui sebelumnya.');
            }
            $sourcePath = $pendaftaran->pas_foto;
            $fileName = Str::afterLast($sourcePath, '/');
            $newPath = 'foto_magang/' . $fileName;
            if (Storage::disk('public')->exists($sourcePath)) {
                Storage::disk('public')->copy($sourcePath, $newPath);
            } else {
                 return back()->withErrors(['foto' => 'File foto pendaftar tidak ditemukan. Approval dibatalkan.']);
            }
            Magang::create([
                'user_id' => $pendaftaran->user_id,
                'nama' => $pendaftaran->nama_pendaftar,
                'asal_kampus' => $pendaftaran->asal_kampus,
                'prodi' => $pendaftaran->prodi,
                'tanggal_mulai' => $pendaftaran->tanggal_mulai,
                'tanggal_selesai' => $pendaftaran->tanggal_selesai,
                'foto' => $newPath,
            ]);
            $pendaftaran->status = 'approved';
            $pendaftaran->remarks = $remarks;
            $pendaftaran->save();
            Mail::to($pendaftaran->email)->send(new PendaftaranStatusMail($pendaftaran));
            return redirect()->route('daftar.index')->with('success', 'Pendaftaran disetujui! Data telah dipindahkan & notifikasi email terkirim.');
        
        } else {
            $pendaftaran->status = $status;
            $pendaftaran->remarks = $remarks;
            $pendaftaran->save();
            Mail::to($pendaftaran->email)->send(new PendaftaranStatusMail($pendaftaran));
            $message = $status == 'rejected' ? 'Pendaftaran ditolak.' : 'Pendaftaran disetujui dengan syarat.';
            return redirect()->route('daftar.index')->with('success', $message . ' Notifikasi email terkirim.');
        }
    }

    /**
     * Memaksa download file pendaftaran berdasarkan nama field.
     */
    public function downloadFile(Pendaftaran $pendaftaran, $field)
    {
        if (!auth()->user()->isAdmin() && $pendaftaran->user_id != auth()->id()) {
            abort(403, 'AKSES DITOLAK');
        }

        $allowedFields = ['surat_permohonan', 'surat_kampus', 'pas_foto'];

        if (!in_array($field, $allowedFields)) {
            abort(404, 'Jenis file tidak valid.');
        }

        $filePath = $pendaftaran->{$field}; 

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($filePath);
    }
    
    /**
     * Mencatat konfirmasi kehadiran dari pendaftar.
     */
    public function konfirmasiKehadiran(Pendaftaran $pendaftaran)
    {
        $message = '';
        $isError = false;

        if ($pendaftaran->status != 'approved') {
            $message = 'Konfirmasi gagal. Status pendaftaran Anda belum disetujui.';
            $isError = true;
        
        } elseif ($pendaftaran->konfirmasi_at) {
            $message = 'Anda sudah melakukan konfirmasi pada tanggal ' . Carbon::parse($pendaftaran->konfirmasi_at)->format('d F Y, H:i') . ' WIB.';
            $isError = false;
        
        } else {
            $pendaftaran->konfirmasi_at = now();
            $pendaftaran->save();
            $message = 'Terima kasih! Kehadiran Anda telah berhasil dikonfirmasi.';
            $isError = false;
        }

        return view('daftar.konfirmasi-sukses', [
            'pendaftaran' => $pendaftaran,
            'message' => $message,
            'isError' => $isError,
        ]);
    }
}