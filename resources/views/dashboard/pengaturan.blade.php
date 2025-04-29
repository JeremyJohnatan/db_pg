@extends('layouts.app')

@section('title', 'Pengaturan Akun | PG Rajawali I')

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #004a94;
            --secondary: #f5f7fb;
            --sidebar-width: 250px;
        }
        
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: var(--secondary);
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
        }
        
        /* Top Bar */
        .top-bar {
            background-color: white;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .datetime {
            font-size: 0.9rem;
            color: #555;
        }
        
        /* Notification and Profile */
        .user-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .notification-badge {
            position: relative;
            cursor: pointer;
        }
        
        .notification-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #ff4757;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        /* Quick Access */
        .quick-access {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .quick-card {
            background-color: white;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            transition: transform 0.2s;
            cursor: pointer;
        }
        
        .quick-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .quick-card i {
            font-size: 1.5rem;
            color: var(--primary);
            margin-bottom: 10px;
        }
        
        /* Notes Section */
        .notes-section {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        
        .notes-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .notes-content {
            min-height: 100px;
            border: 1px dashed #ddd;
            border-radius: 6px;
            padding: 10px;
        }
        
        /* Report Schedule */
        .schedule-section {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .schedule-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        .schedule-item:last-child {
            border-bottom: none;
        }
        
        /* Dropdown Menu */
        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 0.5rem;
        }
        
        .dropdown-item {
            padding: 0.5rem 1rem;
            border-radius: 4px;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                z-index: 1000;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .menu-toggle {
                display: block !important;
            }
        }
    </style>


@section('content')
    <!-- Sidebar -->
    <!--<div class="sidebar">
        <div class="sidebar-brand">
            PG Rajawali I<br>
            <small>Unit PG Notfet Bam</small>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="#" class="nav-link active">
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-box"></i>
                    Analisis Produk
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-industry"></i>
                    Analisis Pabrik
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-file-alt"></i>
                    Laporan
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-users"></i>
                    Users
                </a>
            </div>
        </nav>
    </div>-->

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar with Date, Time, and User Info -->
        <div class="top-bar">
            <div class="datetime">
                <span id="current-date"></span>
                <span id="current-time" class="ms-2"></span>
            </div>
            
            <div class="user-actions">
                <!-- Notifications -->
                <div class="notification-badge" data-bs-toggle="dropdown">
                    <i class="fas fa-bell"></i>
                    <span class="notification-count">3</span>
                    
                    <!-- Notification Dropdown -->
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">Notifikasi Terbaru</h6></li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <small>Laporan produksi telah selesai</small>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <small>Pembaruan sistem tersedia</small>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <small>Meeting hari ini pukul 14:00</small>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center" href="#">Lihat Semua</a></li>
                    </ul>
                </div>
                
                <!-- User Profile -->
                <div class="user-profile dropdown">
                    <div data-bs-toggle="dropdown">
                        <div class="user-avatar">A</div>
                        <span>Halo, Admin</span>
                    </div>
                    
                    <!-- Profile Dropdown -->
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user me-2"></i> Profil Pengguna
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-cog me-2"></i> Pengaturan
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-sticky-note me-2"></i> Catatan Saya
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="#">
                                <i class="fas fa-sign-out-alt me-2"></i> Keluar
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Quick Access/Favorites -->
        <h5 class="mb-3">Pintasan Cepat</h5>
        <div class="quick-access">
            <div class="quick-card">
                <i class="fas fa-file-export"></i>
                <div>Ekspor Laporan</div>
            </div>
            <div class="quick-card">
                <i class="fas fa-chart-line"></i>
                <div>Analisis Cepat</div>
            </div>
            <div class="quick-card">
                <i class="fas fa-plus-circle"></i>
                <div>Tambah Produk</div>
            </div>
            <div class="quick-card">
                <i class="fas fa-calendar-check"></i>
                <div>Jadwal Saya</div>
            </div>
            <div class="quick-card">
                <i class="fas fa-bookmark"></i>
                <div>Favorit</div>
            </div>
        </div>
        
        <!-- Personal Notes -->
        <div class="notes-section">
            <div class="notes-header">
                <h5>Catatan Pribadi</h5>
                <button class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Tambah Catatan
                </button>
            </div>
            <div class="notes-content">
                <p class="text-muted">Klik untuk menambahkan catatan pribadi...</p>
                <!-- Example note would go here -->
            </div>
        </div>
        
        <!-- Report Schedule -->
        <div class="schedule-section">
            <h5 class="mb-3">Jadwal Laporan</h5>
            <div class="schedule-item">
                <div>
                    <strong>Laporan Harian Produksi</strong>
                    <div class="text-muted small">Setiap hari pukul 08:00</div>
                </div>
                <div class="text-end">
                    <span class="badge bg-success">Aktif</span>
                    <div class="text-muted small">Berikutnya: besok</div>
                </div>
            </div>
            <div class="schedule-item">
                <div>
                    <strong>Laporan Mingguan</strong>
                    <div class="text-muted small">Setiap Senin pukul 09:00</div>
                </div>
                <div class="text-end">
                    <span class="badge bg-success">Aktif</span>
                    <div class="text-muted small">Berikutnya: 2 hari</div>
                </div>
            </div>
            <div class="schedule-item">
                <div>
                    <strong>Laporan Bulanan</strong>
                    <div class="text-muted small">Tanggal 1 setiap bulan</div>
                </div>
                <div class="text-end">
                    <span class="badge bg-warning text-dark">Akan datang</span>
                    <div class="text-muted small">Berikutnya: 12 hari</div>
                </div>
            </div>
        </div>
    </div>
@endsection    



<!-- JavaScript -->
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update date and time
        function updateDateTime() {
            const now = new Date();
            
            // Format date: DD/MM/YYYY
            const dateStr = now.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
            
            // Format time: HH:MM:SS
            const timeStr = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            });
            
            document.getElementById('current-date').textContent = dateStr;
            document.getElementById('current-time').textContent = timeStr;
        }
        
        // Update immediately and then every second
        updateDateTime();
        setInterval(updateDateTime, 1000);
        
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            // This would be used for mobile menu toggle if implemented
        });
    </script>
@endsection