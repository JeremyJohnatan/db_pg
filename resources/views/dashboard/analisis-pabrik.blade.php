@extends('layouts.app')

@section('title', 'Analisis Pabrik | PG Rajawali I')

@section('styles')
<style>
 
</style>
@endsection

@section('content')
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
        // Mengambil data dari controller yang dikirim ke view
        const labels = @json($labels ?? []);
        const produksiData = @json($produksiData ?? []);
        const penjualanData = @json($penjualanData ?? []);
        let comparisonChart;
        
        // Inisialisasi grafik perbandingan
        initChart();
        
        // Menambahkan efek bayangan pada navbar saat scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 10) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
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
                
                window.location.href = `{{ route('dashboard.analisis-pabrik') }}?tanggal_mulai=${tanggalMulai}&tanggal_akhir=${tanggalAkhir}`;
            });
        }
        
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