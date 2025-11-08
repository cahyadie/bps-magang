<?php
// app/Http/Controllers/MagangController.php

namespace App\Http\Controllers;

use App\Models\Magang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MagangController extends Controller
{
    public function index()
    {
        $magangs = Magang::latest()->paginate(12);
        return view('magang.index', compact('magangs'));
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
        ]);

        // Upload foto
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('foto-magang', 'public');
        }

        Magang::create($validated);

        return redirect()->route('magang.index')->with('success', 'Data magang berhasil ditambahkan!');
    }

    public function show(Magang $magang)
    {
        return view('magang.show', compact('magang'));
    }

    public function edit(Magang $magang)
    {
        return view('magang.edit', compact('magang'));
    }

    public function update(Request $request, Magang $magang)
    {
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
        // Hapus foto
        if ($magang->foto && Storage::disk('public')->exists($magang->foto)) {
            Storage::disk('public')->delete($magang->foto);
        }

        $magang->delete();

        return redirect()->route('magang.index')->with('success', 'Data magang berhasil dihapus!');
    }
}
