@extends('layouts.app')

@section('title', 'Analisis Pabrik | PG Rajawali I')

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
    }
    .navbar .search-bar {
        position: relative;
    }
    .navbar .search-bar input {
        padding-left: 40px; /* Space for the icon */
        border-radius: 20px;
        border: 1px solid #e0e0e0;
        background-color: #f5f7fb;
    }
    .navbar .search-bar .search-icon {
        position: absolute;
        left: 15px;
        top: 48%;
        transform: translateY(-50%);
        color: #a0a0a0;
        pointer-events: none; /* Ensures icon doesn't interfere with input */
        z-index: 10;
    }
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }
    .card-title {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 8px;
    }
    .card-value {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 0;
    }
    .chart-container {
        height: 250px;
        background-color: #f0f5ff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #a0a0a0;
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
    }

    /* Style khusus untuk analisis pabrik */
    .factory-card {
        background-color: #d6e4f0;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .factory-header {
        display: flex;
        justify-content: space-between;
        padding: 15px 20px;
        border-bottom: 1px solid #c0d6e8;
        font-weight: bold;
    }
    .factory-content {
        padding: 5px 20px;
    }
    .factory-row {
        display: flex;
        align-items: center;
        padding: 15px 0;
    }
    .factory-row:not(:last-child) {
        border-bottom: 1px solid #c0d6e8;
    }
    .factory-name {
        width: 80px;
        font-weight: 500;
    }
    .progress-container {
        flex-grow: 1;
        height: 25px;
        background-color: #e9ecef;
        margin: 0 20px;
    }
    .progress-bar {
        height: 100%;
        background-color: #6c757d;
    }
    .factory-value {
        width: 100px;
        text-align: right;
        font-weight: 500;
    }
</style>
@endsection

@section('content')
<!-- Sidebar -->
<div class="sidebar d-flex flex-column">
    <div class="logo d-flex align-items-center">
        <!-- Updated logo with Laravel asset() helper -->
        <img src="{{ asset('assets/images/logo.png') }}" alt="PG Rajawali I">
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
        <a href="{{ route('dashboard.analisis-pabrik') }}" class="nav-link active">
            <div class="d-flex align-items-center">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <i class="fas fa-industry text-primary"></i>
                </div>
                <span>Analisis Pabrik</span>
            </div>
        </a>
        <a href="{{ route('dashboard.laporan') }}" class="nav-link">
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

<!-- Main Content -->
<div class="main-content">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light mb-4">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <select class="form-select form-select-sm me-3">
                    <option selected>30 Hari Terakhir</option>
                    <option>60 Hari Terakhir</option>
                    <option>90 Hari Terakhir</option>
                </select>
            </div>
            <div class="d-flex align-items-center">
                <div class="search-bar me-3">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="form-control" placeholder="Search">
                </div>
                <div class="d-flex align-items-center">
                    <span class="me-2">Halo, {{ Auth::user()->name }}</span>
                    <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Dashboard Content -->
    <div class="container-fluid">
        <!-- Factory Data Card -->
        <div class="row">
            <div class="col-12">
                <div class="factory-card">
                    <div class="factory-header">
                        <div>Pabrik</div>
                        <div>Jumlah</div>
                    </div>
                    <div class="factory-content">
                        <div class="factory-row">
                            <div class="factory-name">KB 1</div>
                            <div class="progress-container">
                                <div class="progress-bar" style="width: 60%;"></div>
                            </div>
                            <div class="factory-value">12.500 ton</div>
                        </div>
                        <div class="factory-row">
                            <div class="factory-name">KB 2</div>
                            <div class="progress-container">
                                <div class="progress-bar" style="width: 85%;"></div>
                            </div>
                            <div class="factory-value">18.750 ton</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Comparison Chart Card -->
        <div class="row">
            <div class="col-12">
                <div class="factory-card">
                    <div class="factory-header">
                        <div>Perbandingan Penjualan & Produksi Per Pabrik</div>
                    </div>
                    <div class="factory-content">
                        <div class="chart-container" style="height: 300px;">
                            <canvas id="comparisonChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // JavaScript for handling active menu items
    document.addEventListener('DOMContentLoaded', function() {
        const navLinks = document.querySelectorAll('.sidebar .nav-link');
        
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                // Kode ini dinonaktifkan agar sidebar tidak berubah ketika diklik
                // navLinks.forEach(l => l.classList.remove('active'));
                // this.classList.add('active');
            });
        });
        
        // Initialize chart
        const ctx = document.getElementById('comparisonChart').getContext('2d');
        if (ctx) {
            const comparisonChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['KB 1', 'KB 2'],
                    datasets: [
                        {
                            label: 'Penjualan',
                            data: [10000, 16500],
                            backgroundColor: '#004a94',
                            barPercentage: 0.7,
                            categoryPercentage: 0.7
                        },
                        {
                            label: 'Produksi',
                            data: [12500, 18750],
                            backgroundColor: '#36a2eb',
                            barPercentage: 0.7,
                            categoryPercentage: 0.7
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah (ton)'
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection