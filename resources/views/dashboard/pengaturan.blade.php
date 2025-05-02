@extends('layouts.app')

@section('title', 'Pengaturan Akun | PG Rajawali I')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #004a94;
            --secondary: #f5f7fb;
        }
        
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
        z-index: 1020;
    }
    .sidebar .logo {
        padding: 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        background-color: #004a94;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .sidebar .logo a {
        display: block;
        width: 100%;
        text-align: center;
        cursor: pointer;
    }
    .sidebar .logo a:hover {
        opacity: 0.9;
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
    .sidebar .nav-link .text-primary {
        color: #004a94 !important;
    }
    .main-content {
        margin-left: 250px;
        padding-top: 80px;
        padding-left: 20px;
        padding-right: 20px;
        padding-bottom: 20px;
    }
    .navbar {
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        position: fixed;
        top: 0;
        right: 0;
        left: 250px;
        z-index: 1030;
        width: calc(100% - 250px);
    }
    .navbar .search-bar {
        position: relative;
    }
    .navbar .search-bar input {
        padding-left: 40px;
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
        pointer-events: none;
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
    .profile-dropdown {
        position: relative;
    }
    .profile-dropdown .dropdown-menu {
        right: 0;
        left: auto;
    }
        /* Main Content */
        .main-content {
            padding: 20px;
            padding-top: 80px;
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
            flex-direction: row;
        }
        
        .notification-badge {
    position: relative;
    font-size: 1.5rem; /* Ukuran icon bell diperbesar */
    cursor: pointer;
}

.notification-badge i {
    font-size: inherit; /* Ikuti ukuran parent */
}

.notification-count {
    position: absolute;
    top: -10px; /* Posisi disesuaikan */
    right: -10px;
    background-color: #ff4757;
    color: white;
    border-radius: 50%;
    width: 18px; /* Lebar diperbesar */
    height: 18px; /* Tinggi diperbesar */
    font-size: 0.9rem; /* Ukuran angka diperbesar */
    display: flex;
    align-items: center;
    justify-content: center;
}
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 0px;
            cursor: pointer;
            order: 1; /* Profil akan di kiri */
            margin-right: 15px; /* Jarak antara profil dan notifikasi */

        }
     
        .user-profile .user-avatar {
         margin-left: 8px; /* Memberikan jarak antara avatar dan teks */
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
               
        /* Navbar */
        .navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: fixed;
            top: 0;
            right: 0;
            left: 250px;
            z-index: 1030;
            width: calc(100% - 250px);
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 20px;
        }

        .top-bar {
        position: sticky;
        top: 0;
        z-index: 1000;
        background: white;
    }

        .page-title {
            flex: 1; /* Mengambil ruang tersisa */
            margin-right: 20px;
        }

        .page-title h4 {
            margin: 0;
            font-weight: 600;
            color: var(--primary);
        }

        .datetime {
            margin-right: auto; /* Mendukung responsive layout */
        }

        @media (max-width: 768px) {
    .page-title h4 {
        font-size: 1rem;
    }
}        
        
     @endsection

@section('content')   
    <!-- Navbar -->
    <nav class="navbar">
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
            
            <!-- title -->
            <div class="top-bar">
            <!-- Tambahkan ini sebagai elemen pertama -->
            <div class="page-title">
                <h6>Pengaturan Akun</h6>
            </div>
    

            <!-- User Profile -->
            <div class="user-profile dropdown">
                <div data-bs-toggle="dropdown" class="d-flex align-items-center">
                    <div class="user-avatar me-2">A</div>

                </div>
                
                <!-- Profile Dropdown -->
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('profile') }}">
                            <i class="fas fa-user me-2"></i> Profil Pengguna
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('pengaturan') }}">
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
                        <a class="dropdown-item text-danger" href="{{ route('logout') }}">
                            <i class="fas fa-sign-out-alt me-2"></i> Keluar
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Date and Time -->
        <div class="top-bar">
            <div class="datetime">
                <span id="current-date"></span>
                <span id="current-time" class="ms-2"></span>
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
    </script>
@endsection