<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnalisisPabrikController;
use App\Http\Controllers\AnalisisProdukController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\DashboardController;

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
    
    // Dashboard route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Analisis Produk routes
    Route::get('/dashboard/analisis-produk', [AnalisisProdukController::class, 'index'])
        ->name('dashboard.analisis-produk');
    
    // Analisis Pabrik routes
    Route::get('/dashboard/analisis-pabrik', [AnalisisPabrikController::class, 'index'])
        ->name('dashboard.analisis-pabrik');
    
    // Laporan routes
    Route::get('/dashboard/laporan', function () {
        return view('dashboard.laporan');
    })->name('dashboard.laporan');
    
    // API routes for AJAX
    Route::prefix('api')->group(function () {
        
        // Routes dari AnalisisProdukController
        Route::get('/detail-produk', [AnalisisProdukController::class, 'getDetailProduk']);
        Route::get('/search-product', [AnalisisProdukController::class, 'searchProduct']);
        Route::get('/production-analysis', [AnalisisProdukController::class, 'getProductionAnalysis']);
        Route::get('/product-trends', [AnalisisProdukController::class, 'getProductTrends']);
        
        // Routes dari ProdukController (jika ingin digunakan)
        // Route::get('/detail-produk', [ProdukController::class, 'detailProduk']);
        // Route::get('/search-product', [ProdukController::class, 'searchProduct']);
        // Route::get('/production-analysis', [ProdukController::class, 'productionAnalysis']);
        // Route::get('/product-trends', [ProdukController::class, 'productTrends']);
    });
});