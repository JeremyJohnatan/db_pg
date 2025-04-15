@extends('layouts.app')

@section('title', 'Analisis Produk | PG Rajawali I')

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
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        background-color: white;
        display: flex;
        justify-content: center;
        align-items: center;
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
        margin-right: 20px;
    }
    .navbar .search-bar input {
        padding-left: 35px;
        border-radius: 20px;
        border: 1px solid #e0e0e0;
        background-color: #f5f7fb;
    }
    .navbar .search-bar i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
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
    
    /* Analisis Produk specific styles */
    .page-title {
        font-size: 1.8rem;
        font-weight: bold;
        margin-bottom: 20px;
    }
    .content-box {
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    .product-table {
        width: 100%;
        border-collapse: collapse;
    }
    .product-table th {
        text-align: left;
        padding: 15px 20px;
        border-bottom: 1px solid #b0c4de;
        font-weight: bold;
        color: #333;
    }
    .product-table td {
        padding: 15px 20px;
        border-bottom: 1px solid #b0c4de;
    }
    .product-table tr:last-child td {
        border-bottom: none;
    }
    .bar-container {
        width: 100%;
        background-color: #8a8a8a;
        height: 30px;
        border-radius: 4px;
    }
</style>
@endsection

@section('content')
<!-- Sidebar -->
<div class="sidebar d-flex flex-column">
    <div class="logo d-flex align-items-center justify-content-center" style="background-color: #004a94;">
        <img src="http://localhost/db_pg/public/assets/images/logo.png" alt="PG Rajawali I" class="img-fluid" style="max-width: 100%; padding: 10px;">
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
        <a href="{{ route('dashboard.analisis-produk') }}" class="nav-link active">
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
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" class="form-control" placeholder="Search">
                </div>
                <div class="d-flex align-items-center">
                    <span class="me-2">Halo, {{ Auth::user()->name }}</span>
                    <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Page Title -->
    <h1 class="page-title">Analisis Produk</h1>
    
    <!-- Content Boxes in Row Layout -->
    <div class="row mt-4">
        <!-- Analisis Penjualan Box (Left) -->
        <div class="col-md-6">
            <div class="content-box h-100">
                <h5 class="card-title mb-3">Analisis Penjualan</h5>
                <div class="chart-container">
                    <canvas id="salesChart" width="400" height="200"></canvas>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const ctx = document.getElementById('salesChart').getContext('2d');
                        const salesChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: ['Gula Bulk', 'Gula Kemasan 1Kg'],
                                datasets: [{
                                    label: 'Penjualan (ton)',
                                    data: [7520, 5680],
                                    backgroundColor: [
                                        '#004a94',
                                        '#0066cc'
                                    ],
                                    borderColor: [
                                        '#004a94',
                                        '#0066cc'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'Ton'
                                        }
                                    },
                                    x: {
                                        title: {
                                            display: true,
                                            text: 'Produk'
                                        }
                                    }
                                }
                            }
                        });
                    });
                </script>
            </div>
        </div>
        
        <!-- Data Table Box (Right) -->
        <div class="col-md-6">
            <div class="content-box h-100">
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Penjualan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Gula Bulk</td>
                            <td>
                                <span class="fw-bold">7.520 ton</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Gula Kemasan 1Kg</td>
                            <td>
                                <span class="fw-bold">5.680 ton</span>
                            </td>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // JavaScript for handling active menu items
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