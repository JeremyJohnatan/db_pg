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
        z-index: 1020; /* Nilai z-index lebih rendah dari navbar */
    }
    .sidebar .logo {
        padding: 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        background-color: white;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    /* Menambahkan style untuk logo agar terlihat dapat diklik */
    .sidebar .logo a {
        display: block;
        width: 100%;
        text-align: center;
        cursor: pointer;
    }
    .sidebar .logo a:hover {
        opacity: 0.9;
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
    .sidebar .nav-link .text-primary {
        color: #004a94 !important;
    }
    .main-content {
        margin-left: 250px;
        padding-top: 80px; /* Sesuaikan dengan tinggi navbar */
        padding-left: 20px;
        padding-right: 20px;
        padding-bottom: 20px;
    }
    .navbar {
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        position: fixed; /* Membuat navbar tetap berada di posisinya */
        top: 0; /* Posisi di bagian atas */
        right: 0; /* Posisi di bagian kanan */
        left: 250px; /* Sesuaikan dengan lebar sidebar (250px) */
        z-index: 1030; /* Nilai z-index yang lebih tinggi untuk memastikan navbar di atas semua konten */
        width: calc(100% - 250px); /* Lebar navbar harus dikurangi lebar sidebar */
        transition: box-shadow 0.3s ease; /* Transisi yang halus saat scroll */
    }
    .navbar.scrolled {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Efek bayangan saat di-scroll */
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
        cursor: pointer;
    }
    /* Tambahan style untuk dropdown profile */
    .profile-dropdown {
        position: relative;
    }
    .profile-dropdown .dropdown-menu {
        right: 0;
        left: auto;
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
        <!-- Menambahkan link ke logo untuk kembali ke dashboard -->
        <a href="{{ route('dashboard') }}" title="Kembali ke Dashboard">
            <img src="{{ asset('assets/images/logo.png') }}" alt="PG Rajawali I" class="img-fluid" style="max-width: 100%; padding: 10px;">
        </a>
    </div>
    <div class="nav flex-column mt-4">
        <a href="{{ route('dashboard') }}" class="nav-link">
            <div class="d-flex align-items-center">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <i class="fas fa-home text-primary"></i>
                </div>
                <span>Dashboard</span>
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
    <!-- Log out link removed from sidebar -->
</div>

<!-- Main Content -->
<div class="main-content">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light mb-4">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <!-- Mengganti dropdown dengan filter kalender -->
                <div class="input-group me-3">
                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                    <input type="date" class="form-control" id="tanggal-mulai" name="tanggal_mulai" value="{{ $tanggal_mulai ?? '' }}">
                </div>
                <div class="input-group me-3">
                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                    <input type="date" class="form-control" id="tanggal-akhir" name="tanggal_akhir" value="{{ $tanggal_akhir ?? '' }}">
                </div>
                <button class="btn btn-primary btn-sm" id="filter-tanggal">Filter</button>
            </div>
            <div class="d-flex align-items-center">
                <div class="search-bar me-3">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="form-control" id="searchInput" placeholder="Search" onkeyup="searchProducts()">
                </div>
                <!-- Profile dropdown menu -->
                <div class="dropdown profile-dropdown">
                    <div class="d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="me-2">Halo, {{ Auth::user()->name }}</span>
                        <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profil</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Pengaturan</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i> Log Out
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
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
        
        // Menambahkan event listener untuk efek bayangan pada navbar saat scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 10) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
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

        // Set default tanggal (1 Januari 2024 dan hari ini)
        const setDefaultDates = () => {
            const today = new Date();
            const startOfYear = new Date('2024-01-01');
            
            const formatDate = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };
            
            // Hanya set default jika tanggal belum diset
            if (!document.getElementById('tanggal-mulai').value) {
                document.getElementById('tanggal-mulai').value = formatDate(startOfYear);
            }
            if (!document.getElementById('tanggal-akhir').value) {
                document.getElementById('tanggal-akhir').value = formatDate(today);
            }
        };
        
        setDefaultDates();

        // Listener untuk filter tanggal
        const filterTanggalBtn = document.getElementById('filter-tanggal');
        if (filterTanggalBtn) {
            filterTanggalBtn.addEventListener('click', function() {
                const tanggalMulai = document.getElementById('tanggal-mulai').value;
                const tanggalAkhir = document.getElementById('tanggal-akhir').value;
                
                if (!tanggalMulai || !tanggalAkhir) {
                    alert('Silakan pilih tanggal mulai dan tanggal akhir');
                    return;
                }
                
                window.location.href = `{{ route('dashboard.analisis-produk') }}?tanggal_mulai=${tanggalMulai}&tanggal_akhir=${tanggalAkhir}`;
            });
        }
    });
    
    // Fungsi untuk memuat data detail produk
    function loadDetailProductData() {
        // Ambil parameter tanggal dari URL jika ada
        const urlParams = new URLSearchParams(window.location.search);
        const tanggalMulai = urlParams.get('tanggal_mulai');
        const tanggalAkhir = urlParams.get('tanggal_akhir');
        
        // Buat URL dengan parameter tanggal
        let apiUrl = '/api/detail-produk';
        if (tanggalMulai && tanggalAkhir) {
            apiUrl += `?tanggal_mulai=${tanggalMulai}&tanggal_akhir=${tanggalAkhir}`;
        }
        
        fetch(apiUrl)
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
        // Ambil parameter tanggal dari URL jika ada
        const urlParams = new URLSearchParams(window.location.search);
        const tanggalMulai = urlParams.get('tanggal_mulai');
        const tanggalAkhir = urlParams.get('tanggal_akhir');
        
        // Buat URL dengan parameter tanggal
        let apiUrl = '/api/production-analysis';
        if (tanggalMulai && tanggalAkhir) {
            apiUrl += `?tanggal_mulai=${tanggalMulai}&tanggal_akhir=${tanggalAkhir}`;
        }
        
        fetch(apiUrl)
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
        // Ambil parameter tanggal dari URL jika ada
        const urlParams = new URLSearchParams(window.location.search);
        const tanggalMulai = urlParams.get('tanggal_mulai');
        const tanggalAkhir = urlParams.get('tanggal_akhir');
        
        // Buat URL dengan parameter tanggal
        let apiUrl = '/api/product-trends';
        if (tanggalMulai && tanggalAkhir) {
            apiUrl += `?tanggal_mulai=${tanggalMulai}&tanggal_akhir=${tanggalAkhir}`;
        }
        
        fetch(apiUrl)
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
            // Ambil parameter tanggal dari URL jika ada
            const urlParams = new URLSearchParams(window.location.search);
            const tanggalMulai = urlParams.get('tanggal_mulai');
            const tanggalAkhir = urlParams.get('tanggal_akhir');
            
            // Buat URL dengan parameter tanggal dan keyword
            let apiUrl = `/api/search-product?keyword=${keyword}`;
            if (tanggalMulai && tanggalAkhir) {
                apiUrl += `&tanggal_mulai=${tanggalMulai}&tanggal_akhir=${tanggalAkhir}`;
            }
            
            fetch(apiUrl)
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