<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MagangController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\DashboardController; 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

// RUTE KONFIRMASI PUBLIK (Bisa diakses tanpa login)
Route::get('/konfirmasi/{pendaftaran}', [PendaftaranController::class, 'konfirmasiKehadiran'])
    ->name('daftar.konfirmasi');

// --- GRUP YANG MEMBUTUHKAN LOGIN ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- GRUP RUTE PENDAFTARAN (UNTUK SEMUA USER) ---
    Route::get('/pendaftaran-magang/create', [PendaftaranController::class, 'create'])->name('daftar.create');
    Route::get('/pendaftaran-magang', [PendaftaranController::class, 'index'])->name('daftar.index');
    Route::post('/pendaftaran-magang', [PendaftaranController::class, 'store'])->name('daftar.store');
    
    // Edit & Update
    Route::get('/pendaftaran-magang/{pendaftaran}/edit', [PendaftaranController::class, 'edit'])->name('daftar.edit');
    Route::put('/pendaftaran-magang/{pendaftaran}', [PendaftaranController::class, 'update'])->name('daftar.update');
    
    // Show Detail
    Route::get('/pendaftaran-magang/{pendaftaran}', [PendaftaranController::class, 'show'])->name('daftar.show');
    
    // [PENTING] Route AJAX untuk Dropdown Kabupaten
    // Namanya disesuaikan menjadi 'ajax.kabupaten' agar sinkron dengan script di View
    Route::post('/get-kabupaten', [PendaftaranController::class, 'getKabupaten'])->name('ajax.kabupaten');
    
    // Export PDF
    Route::get('/daftar/{id}/export-pdf', [PendaftaranController::class, 'exportPdf'])->name('daftar.exportPdf');
    
    // Delete Pendaftaran
    Route::delete('/pendaftaran-magang/{pendaftaran}', [PendaftaranController::class, 'destroy'])->name('daftar.destroy');


    // --- GRUP RUTE DATA MAGANG ---
    Route::get('/magang', [MagangController::class, 'index'])->name('magang.index');
    
    // Rute ini sekarang bisa diakses admin ATAU user pemilik data
    Route::get('/magang/{magang}/edit', [MagangController::class, 'edit'])->name('magang.edit');
    Route::put('/magang/{magang}', [MagangController::class, 'update'])->name('magang.update');

    
    // --- GRUP KHUSUS ADMIN ---
    Route::middleware('admin')->group(function () {
        // Rute Admin untuk Magang
        Route::get('/magang/create', [MagangController::class, 'create'])->name('magang.create'); 
        Route::post('/magang', [MagangController::class, 'store'])->name('magang.store');
        Route::delete('/magang/{magang}', [MagangController::class, 'destroy'])->name('magang.destroy');
        
        // RUTE TINDAKAN ADMIN (Pendaftaran)
        Route::post('/pendaftaran-magang/{pendaftaran}/status', [PendaftaranController::class, 'updateStatus'])->name('daftar.updateStatus');
        Route::get('/pendaftaran-magang/{pendaftaran}/download/{field}', [PendaftaranController::class, 'downloadFile'])->name('daftar.downloadFile');

        // Manajemen User
        Route::resource('users', UserController::class)->except(['show', 'create', 'store']);
    });

    // Rute 'show' untuk Magang (semua user auth bisa lihat)
    Route::get('/magang/{magang}', [MagangController::class, 'show'])->name('magang.show');

});

require __DIR__ . '/auth.php';