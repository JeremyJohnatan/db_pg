<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Rute untuk landing page
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Rute untuk autentikasi
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginAction'])->name('login.action');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute yang dilindungi autentikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');
    
    Route::get('/dashboard/analisis-produk', function () {
        return view('dashboard.analisis-produk');
    })->name('dashboard.analisis-produk');
    
    Route::get('/dashboard/analisis-pabrik', function () {
        return view('dashboard.analisis-pabrik');
    })->name('dashboard.analisis-pabrik');
    
    Route::get('/dashboard/laporan', function () {
        return view('dashboard.laporan');
    })->name('dashboard.laporan');
});

