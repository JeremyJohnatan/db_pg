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
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #a0a0a0;
            padding: 15px;
            border-radius: 8px;
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
            background-color: white;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .factory-header {
            display: flex;
            justify-content: space-between;
            padding: 15px 20px;
            border-bottom: 1px solid #e0e0e0;
            font-weight: bold;
            background-color: #f8f9fa;
            border-radius: 10px 10px 0 0;
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
            border-bottom: 1px solid #e0e0e0;
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
            border-radius: 5px;
            overflow: hidden;
        }
        .progress-bar {
            height: 100%;
            background-color: #004a94;
            border-radius: 5px;
        }
        .factory-value {
            width: 100px;
            text-align: right;
            font-weight: 500;
        }
        
        /* Style debug sudah dihapus */
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
                    <div class="input-group me-3">
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        <input type="date" class="form-control" id="tanggal-mulai" name="tanggal_mulai">
                    </div>
                    <div class="input-group me-3">
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        <input type="date" class="form-control" id="tanggal-akhir" name="tanggal_akhir">
                    </div>
                    <button class="btn btn-primary btn-sm" id="filter-tanggal">Filter</button>
                </div>
                <div class="d-flex align-items-center">
                    <div class="search-bar me-3">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="form-control" placeholder="Search" id="search-input">
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="me-2">Halo, {{ Auth::user()->name }}</span>
                        <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Debug info dihilangkan sepenuhnya -->

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
                            @forelse($progressData as $item)
                            <div class="factory-row">
                                <div class="factory-name">{{ $item['pabrik'] }}</div>
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: {{ $item['persentase'] }}%;"></div>
                                </div>
                                <div class="factory-value">{{ number_format($item['jumlah'], 0, ',', '.') }} ton</div>
                            </div>
                            @empty
                            <div class="factory-row">
                                <p class="text-center w-100">Tidak ada data produksi</p>
                            </div>
                            @endforelse
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
        document.addEventListener('DOMContentLoaded', function() {
            // Kode debug sudah dihapus
            
            // Mengambil data dari controller yang dikirim ke view
            const labels = @json($labels ?? []);
            const produksiData = @json($produksiData ?? []);
            const penjualanData = @json($penjualanData ?? []);
            let comparisonChart;
            
            // Inisialisasi grafik perbandingan
            initChart();
            
            // Fungsi untuk menginisialisasi grafik
            function initChart() {
                const ctx = document.getElementById('comparisonChart').getContext('2d');
                if (ctx) {
                    // Set default data jika data kosong
                    const chartLabels = labels.length > 0 ? labels : ['KB1', 'KB2'];
                    const chartProduksi = produksiData.length > 0 ? produksiData : [0, 0];
                    const chartPenjualan = penjualanData.length > 0 ? penjualanData : [0, 0];
                    
                    comparisonChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: chartLabels,
                            datasets: [
                                {
                                    label: 'Penjualan',
                                    data: chartPenjualan,
                                    backgroundColor: '#004a94',
                                    barPercentage: 0.7,
                                    categoryPercentage: 0.7
                                },
                                {
                                    label: 'Produksi',
                                    data: chartProduksi,
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
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                    labels: {
                                        boxWidth: 15,
                                        padding: 15
                                    }
                                }
                            }
                        }
                    });
                }
            }
            
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
                    
                    fetchDataByDateRange(tanggalMulai, tanggalAkhir);
                });
            }
            
            // Set default tanggal (hari ini dan 30 hari sebelumnya)
            const setDefaultDates = () => {
                const today = new Date();
                const thirtyDaysAgo = new Date();
                thirtyDaysAgo.setDate(today.getDate() - 30);
                
                const formatDate = (date) => {
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                };
                
                document.getElementById('tanggal-mulai').value = formatDate(thirtyDaysAgo);
                document.getElementById('tanggal-akhir').value = formatDate(today);
            };
            
            setDefaultDates();
            
            // Fungsi pencarian
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const searchTerm = this.value.toLowerCase();
                    const factoryRows = document.querySelectorAll('.factory-row');
                    
                    factoryRows.forEach(row => {
                        const pabrikName = row.querySelector('.factory-name').textContent.toLowerCase();
                        if (pabrikName.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                    
                    // Update grafik untuk menampilkan hanya data yang dicari
                    if (searchTerm.length > 0) {
                        const filteredLabels = [];
                        const filteredProduksi = [];
                        const filteredPenjualan = [];
                        
                        labels.forEach((label, index) => {
                            if (label.toLowerCase().includes(searchTerm)) {
                                filteredLabels.push(label);
                                filteredProduksi.push(produksiData[index]);
                                filteredPenjualan.push(penjualanData[index]);
                            }
                        });
                        
                        updateChart(filteredLabels, filteredProduksi, filteredPenjualan);
                    } else {
                        // Jika pencarian kosong, kembalikan ke semua data
                        updateChart(labels, produksiData, penjualanData);
                    }
                });
            }
            
            // Fungsi untuk mengambil data berdasarkan rentang tanggal
            function fetchDataByDateRange(tanggalMulai, tanggalAkhir) {
                fetch(`/dashboard/analisis-pabrik?tanggal_mulai=${tanggalMulai}&tanggal_akhir=${tanggalAkhir}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Update grafik dengan data baru
                    updateChart(data.labels, data.produksiData, data.penjualanData);
                    
                    // Update progress bar
                    updateProgressBars(data.progressData);
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    alert('Terjadi kesalahan saat mengambil data. Silakan coba lagi.');
                });
            }
            
            // Fungsi untuk memperbarui grafik
            function updateChart(labels, produksiData, penjualanData) {
                if (comparisonChart) {
                    comparisonChart.data.labels = labels;
                    comparisonChart.data.datasets[0].data = penjualanData;
                    comparisonChart.data.datasets[1].data = produksiData;
                    comparisonChart.update();
                }
            }
            
            // Fungsi untuk memperbarui progress bar
            function updateProgressBars(progressData) {
                const factoryContent = document.querySelector('.factory-content');
                if (!factoryContent) return;
                
                factoryContent.innerHTML = '';
                
                if (progressData.length === 0) {
                    const row = document.createElement('div');
                    row.className = 'factory-row';
                    row.innerHTML = '<p class="text-center w-100">Tidak ada data produksi</p>';
                    factoryContent.appendChild(row);
                    return;
                }
                
                progressData.forEach(item => {
                    const row = document.createElement('div');
                    row.className = 'factory-row';
                    row.innerHTML = `
                        <div class="factory-name">${item.pabrik}</div>
                        <div class="progress-container">
                            <div class="progress-bar" style="width: ${item.persentase}%;"></div>
                        </div>
                        <div class="factory-value">${new Intl.NumberFormat('id-ID').format(item.jumlah)} ton</div>
                    `;
                    factoryContent.appendChild(row);
                });
            }
        });
    </script>
    @endsection