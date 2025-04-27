<?php

use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnalisisPabrikController;
use App\Http\Controllers\AnalisisProdukController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffController;

// Redirect root ke dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginAction'])->name('login.action');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes — hanya bisa diakses setelah login
Route::middleware(['auth'])->group(function () 
{

    // Dashboard utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/staff', [StaffController::class, 'dashboard'])->name('staff');
    
    // Analisis Produk dan Pabrik
    Route::get('/dashboard/analisis-produk', [AnalisisProdukController::class, 'index'])
        ->name('dashboard.analisis-produk');
    Route::get('/dashboard/analisis-pabrik', [AnalisisPabrikController::class, 'index'])
        ->name('dashboard.analisis-pabrik');

    // Laporan
    Route::get('/dashboard/laporan', function () {
        return view('dashboard.laporan');
    })->name('dashboard.laporan');
    // Route::get("/dashboard/laporan/preview", [LaporanController::class, 'previewLaporan'])->name('laporan.preview');
    Route::get('/laporan/preview/{id}', [LaporanController::class, 'previewLaporan']);

    Route::get('/dashboard/users', [UserController::class, 'index'])->name('dashboard.users');
    Route::resource('users', UserController::class)->except(['show']);

    // API routes untuk AJAX data
    Route::prefix('api')->group(function () {

        Route::controller(AnalisisProdukController::class)->group(function () {
            Route::get('/detail-produk', 'getDetailProduk');
            Route::get('/search-product', 'searchProduct');
            Route::get('/production-analysis', 'getProductionAnalysis');
            Route::get('/product-trends', 'getProductTrends');
        });
        Route::get('/profile', function () {
            return view('dashboard.profile');
        })->name('profile');
        Route::get('/dashboard/users/create', [UserController::class, 'create'])->name('users.create');
    });

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])
        ->name('profile.update')
        ->middleware('auth');

    });

    //pengaturan route
    use App\Http\Controllers\PengaturanController;

Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan');
Route::post('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');

     
    // Tambahkan route untuk password
    Route::get('/users/{user}/password', [UserController::class, 'password'])->name('users.password');
    Route::put('/users/{user}/update-password', [UserController::class, 'updatePassword'])->name('users.update-password');
;