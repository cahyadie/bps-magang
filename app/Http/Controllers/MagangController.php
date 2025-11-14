<?php

namespace App\Http\Controllers;

use App\Models\Magang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; // <-- Import DB

class MagangController extends Controller
{
    public function index(Request $request)
    {
        $query = Magang::query();
        // ✅ 1. Ambil tahun yang tersedia SEBELUM filter diterapkan
        $availableYears = (clone $query)
            ->selectRaw('YEAR(tanggal_mulai) as year')
            ->distinct()
            ->whereNotNull('tanggal_mulai')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->filter(); // Hilangkan nilai null
        // ✅ 2. Ambil nilai filter tahun dari request (default: 'all')
        $selectedYear = $request->input('year', 'all');
        // ✅ 3. Terapkan filter search (nama)
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }
        // ✅ 4. Terapkan filter kampus
        if ($request->filled('kampus')) {
            $query->where('asal_kampus', $request->kampus);
        }
        // ✅ 5. Terapkan filter tahun
        if ($selectedYear !== 'all') {
            $query->whereYear('tanggal_mulai', $selectedYear);
        }
        // ✅ 6. Ambil daftar kampus untuk dropdown (distinct)
        $kampusList = Magang::select('asal_kampus')
            ->distinct()
            ->orderBy('asal_kampus', 'asc')
            ->pluck('asal_kampus')
            ->filter(); // Hilangkan nilai null
        // ✅ 7. Ambil data dengan pagination
        $magangs = $query->orderBy('created_at', 'desc')->paginate(12);
        // ✅ 8. Kirim semua data ke view (termasuk $selectedYear dan $availableYears)
        return view('magang.index', compact('magangs', 'kampusList', 'availableYears', 'selectedYear'));
    
        // Logika untuk AJAX filter (jika Anda pakai)
        if ($request->ajax()) {
            // Jika Anda membuat file partials/card-list.blade.php
            // return view('magang.partials.card-list', compact('magangs'))->render();

            // Jika tidak, kita bisa refresh seluruh container
            // (Untuk saat ini, kita biarkan logic fetch JS Anda yang bekerja)
        }

        return view('magang.index', compact('magangs', 'kampusList'));
    }

    public function create()
    {
        return view('magang.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'asal_kampus' => 'required|string|max:255',
            'prodi' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'link_pekerjaan' => 'nullable|url|max:500',
            'whatsapp' => 'nullable|string|max:20',
            'instagram' => 'nullable|string|max:100',
            'tiktok' => 'nullable|string|max:100',
            'kesan' => 'nullable|string|max:2000',
            'pesan' => 'nullable|string|max:2000',
        ]);

        // ❌ BARIS ERROR DIHAPUS: $validated['created_by'] = auth()->id();

        // Upload foto
        if ($request->hasFile('foto')) {
            // ✅ Nama folder diubah ke 'foto_magang' agar konsisten
            $validated['foto'] = $request->file('foto')->store('foto_magang', 'public');
        }

        // ❌ BARIS ERROR DIHAPUS: Logika $initials dihapus.
        // Model akan otomatis menghitung periode_bulan dan status saat create().

        Magang::create($validated);

        return redirect()->route('magang.index')->with('success', 'Data magang berhasil ditambahkan!');
    }

    public function show(Magang $magang)
    {
        return view('magang.show', compact('magang'));
    }

    public function edit(Magang $magang)
    {
        // ✅ LOGIKA DIPERBARUI: Admin ATAU pemilik yang boleh edit
        if (!auth()->user()->isAdmin() && $magang->user_id != auth()->id()) {
            abort(403, 'AKSES DITOLAK. Anda hanya dapat mengedit data Anda sendiri.');
        }
        return view('magang.edit', compact('magang'));
    }

    public function update(Request $request, Magang $magang)
    {
        // ✅ LOGIKA DIPERBARUI: Admin ATAU pemilik yang boleh update
        if (!auth()->user()->isAdmin() && $magang->user_id != auth()->id()) {
            abort(403, 'AKSES DITOLAK.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'asal_kampus' => 'required|string|max:255',
            'prodi' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'link_pekerjaan' => 'nullable|url|max:500',
            'whatsapp' => 'nullable|string|max:20',
            'instagram' => 'nullable|string|max:100',
            'tiktok' => 'nullable|string|max:100',
            'kesan' => 'nullable|string|max:2000',
            'pesan' => 'nullable|string|max:2000',
        ]);

        if ($request->hasFile('foto')) {
            if ($magang->foto && Storage::disk('public')->exists($magang->foto)) {
                Storage::disk('public')->delete($magang->foto);
            }
            $validated['foto'] = $request->file('foto')->store('foto_magang', 'public');
        }

        $magang->update($validated);

        return redirect()->route('magang.show', $magang)->with('success', 'Data magang berhasil diupdate!');
    }

    public function destroy(Magang $magang)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
        if ($magang->foto && Storage::disk('public')->exists($magang->foto)) {
            Storage::disk('public')->delete($magang->foto);
        }
        $magang->delete();
        return redirect()->route('magang.index')->with('success', 'Data magang berhasil dihapus!');
    }
}
