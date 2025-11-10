<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MagangController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return redirect()->route('magang.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ✅ ROUTES UNTUK SEMUA USER (Admin & User biasa)
    Route::get('/magang', [MagangController::class, 'index'])->name('magang.index');
    Route::get('/magang/create', [MagangController::class, 'create'])->name('magang.create');
    Route::post('/magang', [MagangController::class, 'store'])->name('magang.store');
    Route::get('/magang/{magang}', [MagangController::class, 'show'])->name('magang.show');

    // ✅ ROUTES HANYA UNTUK ADMIN (Edit & Delete)
    Route::middleware('admin')->group(function () {
        Route::get('/magang/{magang}/edit', [MagangController::class, 'edit'])->name('magang.edit');
        Route::put('/magang/{magang}', [MagangController::class, 'update'])->name('magang.update');
        Route::delete('/magang/{magang}', [MagangController::class, 'destroy'])->name('magang.destroy');
    });
});

require __DIR__ . '/auth.php';