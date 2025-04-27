@extends('layouts.app')

@section('title', 'Dashboard | PG Rajawali I')

@section('styles')
<style>
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
</style>
@endsection

@section('content')
    
    <!-- Dashboard Content -->
    <div class="container-fluid">
        <!-- Top Row of Cards -->
        <div class="row">
            <div class="col-md-4">
                <div class="card p-3">
                    <h5 class="card-title">Total Kontrak</h5>
                    <p class="card-value">{{ number_format($totalKontrak ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <h5 class="card-title">Rata - Rata Kontrak</h5>
                    <p class="card-value">{{ number_format($rataRataKontrak ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <h5 class="card-title">Produk Terlaris</h5>
                    <p class="card-value">{{ $produkTerlaris ?? 'Tidak ada data' }}</p>
                </div>
            </div>
        </div>
        
        <!-- Middle Row of Cards -->
        <div class="row">
            <div class="col-md-6">
                <div class="card p-3">
                    <h5 class="card-title">Total Pengambilan</h5>
                    <p class="card-value">{{ number_format($totalPengambilan ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3">
                    <h5 class="card-title">Rata - Rata Produksi</h5>
                    <p class="card-value">{{ number_format($rataRataProduksi ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        
        <!-- Charts Row -->
        <div class="row">
            <div class="col-md-6">
                <div class="card p-3">
                    <h5 class="card-title">Produksi VS Kontrak</h5>
                    <div class="chart-container">
                        <canvas id="produksiKontrakChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3">
                    <h5 class="card-title">Total Penjualan</h5>
                    <div class="chart-container">
                        <canvas id="penjualanChart"></canvas>
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
        // JavaScript for handling active menu items
        const navLinks = document.querySelectorAll('.sidebar .nav-link');
        
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });
        
        // Inisialisasi grafik produksi vs kontrak
        const produksiKontrakCtx = document.getElementById('produksiKontrakChart').getContext('2d');
        const produksiKontrakChart = new Chart(produksiKontrakCtx, {
            type: 'bar',
            data: {
                labels: JSON.parse('{!! $dataBulan ?? "[]" !!}'),
                datasets: [
                    {
                        label: 'Kontrak',
                        data: JSON.parse('{!! $dataKontrak ?? "[]" !!}'),
                        backgroundColor: '#004a94',
                        barPercentage: 0.7,
                        categoryPercentage: 0.7
                    },
                    {
                        label: 'Produksi',
                        data: JSON.parse('{!! $dataProduksi ?? "[]" !!}'),
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
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Bulan'
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
        
        // Inisialisasi grafik penjualan per kategori
        const penjualanCtx = document.getElementById('penjualanChart').getContext('2d');
        const penjualanChart = new Chart(penjualanCtx, {
            type: 'pie',
            data: {
                labels: JSON.parse('{!! $labelKategori ?? "[]" !!}'),
                datasets: [{
                    data: JSON.parse('{!! $dataPenjualan ?? "[]" !!}'),
                    backgroundColor: [
                        '#004a94',
                        '#0066cc',
                        '#3399ff',
                        '#66b2ff',
                        '#99ccff'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 15,
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                                const percentage = total > 0 ? Math.round(value / total * 100) : 0;
                                return `${label}: ${value} ton (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
        
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
                
                window.location.href = `{{ route('dashboard') }}?tanggal_mulai=${tanggalMulai}&tanggal_akhir=${tanggalAkhir}`;
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
    });
</script>
@endsection