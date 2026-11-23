<?php

use App\Http\Controllers\Mahasiswa\MahasiswaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:mahasiswa'])
    ->prefix('mahasiswa')
    ->name('mahasiswa.')
    ->group(function () {
        Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('dashboard');
        Route::get('/mata-kuliah', [MahasiswaController::class, 'katalog'])->name('katalog');
        Route::post('/mata-kuliah/ambil', [MahasiswaController::class, 'ambil'])->name('ambil');
        Route::get('/krs', [MahasiswaController::class, 'krs'])->name('krs');
        Route::get('/jadwal', [MahasiswaController::class, 'jadwal'])->name('jadwal');
        Route::get('/nilai', [MahasiswaController::class, 'nilai'])->name('nilai');
        Route::get('/presensi', [MahasiswaController::class, 'presensi'])->name('presensi');
    });
