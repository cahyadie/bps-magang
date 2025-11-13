<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Magang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\PendaftaranStatusMail;
use Illuminate\Support\Facades\Auth;

class PendaftaranController extends Controller
{
    /**
     * Menampilkan halaman tabel pendaftaran.
     */
    public function index()
    {
        $pendaftarans = Pendaftaran::orderBy('created_at', 'desc')->get();
        return view('daftar.index', compact('pendaftarans'));
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
        // Validasi input (termasuk email dan surat_kampus)
        $validated = $request->validate([
            'nama_pendaftar' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'surat_permohonan' => 'required|file|mimes:pdf|max:2048',
            'surat_kampus' => 'required|file|mimes:pdf|max:2048', // <-- VALIDASI BARU
            'pas_foto' => 'required|file|image|mimes:jpeg,png,jpg|max:1024',
            'asal_kampus' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        // 1. Simpan file Surat Permohonan (PDF)
        $validated['surat_permohonan'] = $request->file('surat_permohonan')->store('surat_permohonan', 'public');

        // 2. Simpan file Surat Kampus (PDF)
        $validated['surat_kampus'] = $request->file('surat_kampus')->store('surat_kampus', 'public');

        // 3. Simpan file Pas Foto (Gambar)
        $validated['pas_foto'] = $request->file('pas_foto')->store('pas_foto', 'public');
        
        $requestData['user_id'] = Auth::id();
        
        // 4. Simpan data ke database
        Pendaftaran::create($validated);

        return redirect()->route('daftar.index')->with('success', 'Pendaftaran berhasil dikirim! Silakan tunggu konfirmasi dari Admin.');
    }

    /**
     * Menampilkan halaman detail pendaftaran.
     */
    public function show(Pendaftaran $pendaftaran)
    {
        return view('daftar.show', compact('pendaftaran'));
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
            // ... (logika approval Anda sudah benar) ...
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
                'nama' => $pendaftaran->nama_pendaftar,
                'asal_kampus' => $pendaftaran->asal_kampus,
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
            // ... (logika reject/conditional Anda sudah benar) ...
            $pendaftaran->status = $status;
            $pendaftaran->remarks = $remarks;
            $pendaftaran->save();
            Mail::to($pendaftaran->email)->send(new PendaftaranStatusMail($pendaftaran));
            $message = $status == 'rejected' ? 'Pendaftaran ditolak.' : 'Pendaftaran disetujui dengan syarat.';
            return redirect()->route('daftar.index')->with('success', $message . ' Notifikasi email terkirim.');
        }
    }

    /**
     * ✅ FUNGSI DOWNLOAD DIPERBARUI
     * Memaksa download file pendaftaran berdasarkan nama field.
     */
    public function downloadFile(Pendaftaran $pendaftaran, $field)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'HANYA ADMIN YANG DIIZINKAN.');
        }

        // Tentukan kolom yang valid untuk di-download
        $allowedFields = ['surat_permohonan', 'surat_kampus', 'pas_foto'];

        if (!in_array($field, $allowedFields)) {
            abort(404, 'Jenis file tidak valid.');
        }

        $filePath = $pendaftaran->{$field}; // misal: $pendaftaran->surat_permohonan

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($filePath);
    }

    public function konfirmasiKehadiran(Pendaftaran $pendaftaran)
    {
        $message = '';
        $isError = false;

        // Cek apakah pendaftar ini memang sudah disetujui
        if ($pendaftaran->status != 'approved') {
            $message = 'Konfirmasi gagal. Status pendaftaran Anda belum disetujui.';
            $isError = true;
        
        // Cek apakah sudah pernah konfirmasi sebelumnya
        } elseif ($pendaftaran->konfirmasi_at) {
            $message = 'Anda sudah melakukan konfirmasi pada tanggal ' . $pendaftaran->konfirmasi_at->format('d F Y, H:i') . ' WIB.';
            $isError = false; // Ini bukan error, hanya info
        
        // Jika lolos, update databasenya
        } else {
            $pendaftaran->konfirmasi_at = now();
            $pendaftaran->save();
            $message = 'Terima kasih! Kehadiran Anda telah berhasil dikonfirmasi.';
            $isError = false;
        }

        // Tampilkan halaman "Terima Kasih"
        return view('daftar.konfirmasi-sukses', [
            'pendaftaran' => $pendaftaran,
            'message' => $message,
            'isError' => $isError,
        ]);
    }
}