@extends('layouts.app')

@section('title', 'Analisis Produk | PG Rajawali I')

@section('styles')
<style>

</style>
@endsection

@section('content')    
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