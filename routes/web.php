<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnalisisPabrikController;
use App\Http\Controllers\AnalisisProdukController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginAction'])->name('login.action');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Analisis routes
    Route::get('/dashboard/analisis-produk', [AnalisisProdukController::class, 'index'])
        ->name('dashboard.analisis-produk');
    Route::get('/dashboard/analisis-pabrik', [AnalisisPabrikController::class, 'index'])
        ->name('dashboard.analisis-pabrik');
    
    // Laporan route
    Route::get('/dashboard/laporan', function () {
        return view('dashboard.laporan');
    })->name('dashboard.laporan');
    
    // Profil route
    Route::get('/profile', function () {
        return view('dashboard.profile');
    })->name('profile');
    
    // User management routes
    Route::get('/dashboard/users', [UserController::class, 'index'])->name('dashboard.users');
    Route::resource('users', UserController::class)->except(['show']);
    
    // API routes for AJAX
    Route::prefix('api')->group(function () {
        // Analisis Produk API endpoints
        Route::controller(AnalisisProdukController::class)->group(function () {
            Route::get('/detail-produk', 'getDetailProduk');
            Route::get('/search-product', 'searchProduct');
            Route::get('/production-analysis', 'getProductionAnalysis');
            Route::get('/product-trends', 'getProductTrends');
        });
        
        // Commented routes have been removed to reduce clutter
    });
});