<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MagangController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\DashboardController; 

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

// RUTE KONFIRMASI PUBLIK
Route::get('/konfirmasi/{pendaftaran}', [PendaftaranController::class, 'konfirmasiKehadiran'])
    ->name('daftar.konfirmasi');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    
    // --- GRUP RUTE PENDAFTARAN (UNTUK SEMUA USER) ---
    Route::get('/pendaftaran-magang', [PendaftaranController::class, 'index'])->name('daftar.index');
    Route::get('/pendaftaran-magang/create', [PendaftaranController::class, 'create'])->name('daftar.create');
    Route::post('/pendaftaran-magang', [PendaftaranController::class, 'store'])->name('daftar.store');


    // --- GRUP RUTE DATA MAGANG ---
    Route::get('/magang', [MagangController::class, 'index'])->name('magang.index');
    Route::post('/magang', [MagangController::class, 'store'])->name('magang.store');
    
    // Rute 'show' yang dinamis dipindahkan ke bawah

    
    Route::middleware('admin')->group(function () {
        // Rute 'create' (spesifik) harus didefinisikan sebelum 'show' (dinamis)
        Route::get('/magang/create', [MagangController::class, 'create'])->name('magang.create'); 
        Route::get('/magang/{magang}/edit', [MagangController::class, 'edit'])->name('magang.edit');
        Route::put('/magang/{magang}', [MagangController::class, 'update'])->name('magang.update');
        Route::delete('/magang/{magang}', [MagangController::class, 'destroy'])->name('magang.destroy');
        
        // RUTE TINDAKAN ADMIN
        Route::get('/pendaftaran-magang/{pendaftaran}', [PendaftaranController::class, 'show'])->name('daftar.show');
        Route::post('/pendaftaran-magang/{pendaftaran}/status', [PendaftaranController::class, 'updateStatus'])->name('daftar.updateStatus');
        Route::get('/pendaftaran-magang/{pendaftaran}/download/{field}', [PendaftaranController::class, 'downloadFile'])->name('daftar.downloadFile');
    });

    // ✅ DIPINDAHKAN: Rute 'show' (dinamis) sekarang ada di akhir,
    // sehingga tidak "menangkap" rute '/magang/create'.
    Route::get('/magang/{magang}', [MagangController::class, 'show'])->name('magang.show');

});

require __DIR__ . '/auth.php';