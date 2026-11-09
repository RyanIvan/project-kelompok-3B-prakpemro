<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


// Halaman Utama Publik (Sebelum Login)
Route::get('/', function () {
    return view('landing');
})->name('home');

// Route Halaman Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
