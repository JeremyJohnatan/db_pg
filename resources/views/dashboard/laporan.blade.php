@extends('layouts.app')

@section('title', 'Laporan | PG Rajawali I')

@section('styles')
<style>
    body {
        background-color: #f5f7fb;
        font-family: Arial, sans-serif;
    }
    .sidebar {
        background-color: #004a94;
        color: white;
        min-height: 100vh;
        position: fixed;
        width: 250px;
    }
    .sidebar .logo {
        padding: 15px;
        font-weight: bold;
        font-size: 1.2rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    .sidebar .logo img {
        max-width: 100%;
        padding: 10px;
    }
    .sidebar .nav-link {
        color: white;
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        transition: all 0.3s;
    }
    .sidebar .nav-link:hover, .sidebar .nav-link.active {
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
    }
    .sidebar .nav-link i {
        width: 20px;
        text-align: center;
    }
    .sidebar .nav-link.logout {
        margin-top: auto;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }
    .sidebar .nav-link .text-primary {
        color: #004a94 !important;
    }
    .main-content {
        margin-left: 250px;
        padding: 20px;
    }
    .navbar {
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .navbar-left {
        display: flex;
        align-items: center;
    }
    .navbar-left .form-select-sm {
        margin-right: 15px;
    }
    .navbar-right {
        display: flex;
        align-items: center;
    }
    .navbar-right .search-bar {
        position: relative;
        margin-right: 20px;
    }
    .navbar-right .search-bar input {
        padding-left: 35px;
        border-radius: 20px;
        border: 1px solid #e0e0e0;
        background-color: #f5f7fb;
        font-size: 0.9rem;
        padding-top: 8px;
        padding-bottom: 8px;
    }
    .navbar-right .search-bar i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #a0a0a0;
    }
    .user-info {
        display: flex;
        align-items: center;
    }
    .user-info span {
        margin-right: 10px;
        font-size: 0.9rem;
    }
    .user-avatar {
        width: 32px;
        height: 32px;
        background-color: #004a94;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.8rem;
    }
    .content-area {
        padding: 20px;
    }
    .report-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
    }
    .report-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .report-card-title {
        color: #333;
        font-size: 1.2rem;
        font-weight: bold;
    }
    .report-actions button.btn-primary {
        background-color: #004a94;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 15px;
        cursor: pointer;
        font-size: 0.9rem;
        transition: background-color 0.3s;
    }
    .report-actions button.btn-primary:hover {
        background-color: #003366;
    }
    .report-table-container {
        overflow-x: auto;
    }
    .report-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    .report-table th, .report-table td {
        border: 1px solid #e0e0e0;
        padding: 10px;
        text-align: left;
        font-size: 0.9rem;
    }
    .report-table th {
        background-color: #f5f7fb;
        font-weight: bold;
        color: #333;
    }
    .no-data {
        color: #777;
        font-style: italic;
    }
</style>
@endsection

@section('content')
<div class="sidebar d-flex flex-column">
    <div class="logo d-flex align-items-center justify-content-center" style="background-color: #004a94;">
        <!-- Updated logo with Laravel asset() helper -->
        <img src="{{ asset('assets/images/logo.png') }}" alt="PG Rajawali I" class="img-fluid" style="max-width: 100%; padding: 10px;">
    </div>
    <div class="nav flex-column mt-4">
        <a href="{{ route('dashboard') }}" class="nav-link">
            <div class="d-flex align-items-center">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <i class="fas fa-home text-primary"></i>
                </div>
                <span>Home</span>
            </div>
        </a>
        <a href="{{ route('dashboard.analisis-produk') }}" class="nav-link">
            <div class="d-flex align-items-center">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <i class="fas fa-box text-primary"></i>
                </div>
                <span>Analisis Produk</span>
            </div>
        </a>
        <a href="{{ route('dashboard.analisis-pabrik') }}" class="nav-link">
            <div class="d-flex align-items-center">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <i class="fas fa-industry text-primary"></i>
                </div>
                <span>Analisis Pabrik</span>
            </div>
        </a>
        <a href="{{ route('dashboard.laporan') }}" class="nav-link active">
            <div class="d-flex align-items-center">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <i class="fas fa-file-alt text-primary"></i>
                </div>
                <span>Laporan</span>
            </div>
        </a>
    </div>
    <a href="{{ route('logout') }}" class="nav-link logout mt-auto" 
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <div class="d-flex align-items-center">
            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                <i class="fas fa-sign-out-alt text-primary"></i>
            </div>
            <span>Log Out</span>
        </div>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</div>

<div class="main-content">
    <nav class="navbar navbar-expand-lg navbar-light mb-4">
        <div class="container-fluid">
            <div class="navbar-left">
                <select class="form-select form-select-sm me-3">
                    <option selected>Tanggal</option>
                    <option value="today">Hari Ini</option>
                    <option value="yesterday">Kemarin</option>
                    <option value="last_week">Minggu Lalu</option>
                    <option value="last_month">Bulan Lalu</option>
                </select>
            </div>
            <div class="navbar-right">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" class="form-control" placeholder="Cari Laporan">
                </div>
                <div class="user-info">
                    <span class="me-2">Halo, {{ Auth::user()->name }}</span>
                    <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                </div>
            </div>
        </div>
    </nav>

    <div class="content-area">
        <div class="report-card">
            <div class="report-card-header">
                <h5 class="report-card-title">Laporan</h5>
                <div class="report-actions">
                    <button class="btn btn-primary">Download Laporan</button>
                </div>
            </div>
            <div class="report-table-container">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jenis Laporan</th>
                            <th>Tanggal Dibuat</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Laporan Produksi Harian</td>
                            <td>14 April 2025</td>
                            <td>Selesai</td>
                            <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i> Unduh</a></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Laporan Analisis Produk Mingguan</td>
                            <td>12 April 2025</td>
                            <td>Selesai</td>
                            <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i> Unduh</a></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Laporan Stok Barang Bulanan</td>
                            <td>10 April 2025</td>
                            <td>Selesai</td>
                            <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i> Unduh</a></td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-center no-data">Tidak ada data laporan yang tersedia.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navLinks = document.querySelectorAll('.sidebar .nav-link');

        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });
    });
</script>
@endsection
