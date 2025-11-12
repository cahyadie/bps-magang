<?php
// app/Http/Controllers/MagangController.php

namespace App\Http\Controllers;

use App\Models\Magang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MagangController extends Controller
{
    public function index(Request $request)
    {
        $query = Magang::query();
        // ✅ FILTER SEARCH (Nama)
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }
        // ✅ FILTER BY UNIVERSITAS
        if ($request->filled('kampus')) {
            $query->where('asal_kampus', $request->kampus);
        }
        // ✅ Get semua kampus untuk dropdown (unique)
        $kampusList = Magang::select('asal_kampus')
            ->distinct()
            ->orderBy('asal_kampus')
            ->pluck('asal_kampus');
        $magangs = $query->orderBy('created_at', 'desc')->paginate(12);
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
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'link_pekerjaan' => 'nullable|url|max:500',
            'whatsapp' => 'nullable|string|max:20',
            'instagram' => 'nullable|string|max:100',
            'tiktok' => 'nullable|string|max:100',
            'kesan' => 'nullable|string|max:2000',
            'pesan' => 'nullable|string|max:2000',
        ]);


        $validated['created_by'] = auth()->id();
        // Upload foto
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('foto-magang', 'public');
        }
        $nameParts = explode(' ', $validated['nama']);
        $initials = '';
        foreach ($nameParts as $part) {
            if (!empty($part)) {
                $initials .= strtoupper($part[0]);
                if (strlen($initials) == 2)
                    break;
            }
        }
        $validated['initials'] = $initials ?: strtoupper(substr($validated['nama'], 0, 2));

        Magang::create($validated);

        return redirect()->route('magang.index')->with('success', 'Data magang berhasil ditambahkan!');
    }

    public function show(Magang $magang)
    {
        return view('magang.show', compact('magang'));
    }

    public function edit(Magang $magang)
    {
        // ✅ DOUBLE CHECK (sudah ada di middleware, tapi lebih aman)
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
        return view('magang.edit', compact('magang'));
    }

    public function update(Request $request, Magang $magang)
    {
        // ✅ DOUBLE CHECK
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'asal_kampus' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'link_pekerjaan' => 'nullable|url|max:500',
            'whatsapp' => 'nullable|string|max:20',
            'instagram' => 'nullable|string|max:100',
            'tiktok' => 'nullable|string|max:100',
            'kesan' => 'nullable|string|max:2000',
            'pesan' => 'nullable|string|max:2000',
        ]);

        // Upload foto baru jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($magang->foto && Storage::disk('public')->exists($magang->foto)) {
                Storage::disk('public')->delete($magang->foto);
            }
            $validated['foto'] = $request->file('foto')->store('foto-magang', 'public');
        }

        $magang->update($validated);

        return redirect()->route('magang.show', $magang)->with('success', 'Data magang berhasil diupdate!');
    }

    public function destroy(Magang $magang)
    {
        // ✅ DOUBLE CHECK
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
