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
                <form id="periodeForm" action="{{ route('dashboard.analisis-produk') }}" method="GET">
                    <select class="form-select form-select-sm me-3" name="periode" id="periode" onchange="document.getElementById('periodeForm').submit()">
                        <option value="30" {{ $periode == 30 ? 'selected' : '' }}>30 Hari Terakhir</option>
                        <option value="60" {{ $periode == 60 ? 'selected' : '' }}>60 Hari Terakhir</option>
                        <option value="90" {{ $periode == 90 ? 'selected' : '' }}>90 Hari Terakhir</option>
                    </select>
                </form>
            </div>
            <div class="d-flex align-items-center">
                <div class="search-bar me-3">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="form-control" id="searchInput" placeholder="Search" onkeyup="searchProducts()">
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
                <h5 class="card-title mb-3">Analisis Penjualan Berdasarkan Kategori</h5>
                <div class="chart-container">
                    <canvas id="salesChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Data Table Box (Right) -->
        <div class="col-md-6">
            <div class="content-box h-100">
                <h5 class="card-title mb-3">Data Penjualan per Kategori</h5>
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Penjualan</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody">
                        @foreach($penjualanPerKategori as $item)
                        <tr>
                            <td>{{ $item->Kategori }}</td>
                            <td>
                                <span class="fw-bold">{{ number_format($item->TotalPenjualan, 2) }} ton</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Analisis Detail Produk -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="content-box">
                <h5 class="card-title mb-3">Detail Penjualan per Jenis Produk</h5>
                <div class="chart-container">
                    <canvas id="detailChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Produksi vs Kontrak -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="content-box">
                <h5 class="card-title mb-3">Perbandingan Kontrak vs Pengambilan</h5>
                <div class="chart-container">
                    <canvas id="comparisonChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tren Penjualan -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="content-box">
                <h5 class="card-title mb-3">Tren Penjualan 6 Bulan Terakhir</h5>
                <div class="chart-container">
                    <canvas id="trendChart" width="400" height="200"></canvas>
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
                navLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });
        
        // Chart.js untuk grafik penjualan
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! $labels !!},
                datasets: [{
                    label: 'Penjualan (ton)',
                    data: {!! $data !!},
                    backgroundColor: [
                        '#004a94',
                        '#0066cc',
                        '#3399ff'
                    ],
                    borderColor: [
                        '#004a94',
                        '#0066cc',
                        '#3399ff'
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
                            text: 'Kategori Produk'
                        }
                    }
                }
            }
        });
        
        // Memuat data detail produk
        loadDetailProductData();
        
        // Memuat data perbandingan kontrak vs pengambilan
        loadComparisonData();
        
        // Memuat data tren penjualan
        loadTrendData();
    });
    
    // Fungsi untuk memuat data detail produk
    function loadDetailProductData() {
        fetch(`/api/detail-produk?periode=${document.getElementById('periode').value}`)
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('detailChart').getContext('2d');
                const detailChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Penjualan (ton)',
                            data: data.data,
                            backgroundColor: '#0066cc',
                            borderColor: '#004a94',
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
                                    text: 'Jenis Produk'
                                }
                            }
                        }
                    }
                });
            });
    }
    
    // Fungsi untuk memuat data perbandingan kontrak vs pengambilan
    function loadComparisonData() {
        fetch('/api/production-analysis')
            .then(response => response.json())
            .then(data => {
                const perbandingan = data.perbandingan;
                const labels = perbandingan.map(item => item.Jenis);
                const kontrakData = perbandingan.map(item => item.TotalKontrak);
                const pengambilanData = perbandingan.map(item => item.TotalPengambilan);
                
                const ctx = document.getElementById('comparisonChart').getContext('2d');
                const comparisonChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Total Kontrak (ton)',
                                data: kontrakData,
                                backgroundColor: '#004a94',
                                borderColor: '#004a94',
                                borderWidth: 1
                            },
                            {
                                label: 'Total Pengambilan (ton)',
                                data: pengambilanData,
                                backgroundColor: '#3399ff',
                                borderColor: '#3399ff',
                                borderWidth: 1
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
                                    text: 'Ton'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Jenis Produk'
                                }
                            }
                        }
                    }
                });
            });
    }
    
    // Fungsi untuk memuat data tren penjualan
    function loadTrendData() {
        fetch('/api/product-trends')
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('trendChart').getContext('2d');
                
                // Siapkan dataset dengan warna berbeda untuk setiap kategori
                const datasets = data.datasets.map((dataset, index) => {
                    const colors = ['#004a94', '#0066cc', '#3399ff', '#66b2ff', '#99ccff'];
                    return {
                        label: dataset.label,
                        data: dataset.data,
                        backgroundColor: colors[index % colors.length],
                        borderColor: colors[index % colors.length],
                        borderWidth: 2,
                        fill: false,
                        tension: 0.4
                    };
                });
                
                const trendChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: datasets
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
                                    text: 'Bulan'
                                }
                            }
                        }
                    }
                });
            });
    }
    
    // Fungsi untuk pencarian produk
    function searchProducts() {
        const keyword = document.getElementById('searchInput').value;
        
        if (keyword.length > 2) {
            fetch(`/api/search-product?keyword=${keyword}`)
                .then(response => response.json())
                .then(data => {
                    // Memperbarui tabel berdasarkan hasil pencarian
                    updateProductTable(data);
                });
        }
    }
    
    // Fungsi untuk memperbarui tabel produk
    function updateProductTable(data) {
        const tableBody = document.getElementById('productTableBody');
        tableBody.innerHTML = '';
        
        data.forEach(item => {
            const row = document.createElement('tr');
            
            const categoryCell = document.createElement('td');
            categoryCell.textContent = item.Kategori;
            
            const salesCell = document.createElement('td');
            const salesValue = document.createElement('span');
            salesValue.classList.add('fw-bold');
            salesValue.textContent = `${Number(item.TotalPenjualan).toLocaleString('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            })} ton`;
            salesCell.appendChild(salesValue);
            
            row.appendChild(categoryCell);
            row.appendChild(salesCell);
            
            tableBody.appendChild(row);
        });
    }
</script>
@endsection