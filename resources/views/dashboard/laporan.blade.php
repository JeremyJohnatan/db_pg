@extends('layouts.app')

@section('title', 'Laporan | PG Rajawali I')

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
    .content-area {
        padding: 20px;
    }
    .report-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
    }
    .report-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .report-card-title {
        color: #333;
        font-size: 1.2rem;
        font-weight: bold;
    }
    .report-actions button.btn-primary {
        background-color: #004a94;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 15px;
        cursor: pointer;
        font-size: 0.9rem;
        transition: background-color 0.3s;
    }
    .report-actions button.btn-primary:hover {
        background-color: #003366;
    }
    .report-table-container {
        overflow-x: auto;
    }
    .report-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    .report-table th, .report-table td {
        border: 1px solid #e0e0e0;
        padding: 10px;
        text-align: left;
        font-size: 0.9rem;
    }
    .report-table th {
        background-color: #f5f7fb;
        font-weight: bold;
        color: #333;
    }
    .no-data {
        color: #777;
        font-style: italic;
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
    .action-buttons {
        display: flex;
        gap: 5px;
    }
    .action-buttons .btn {
        padding: 5px 8px;
        font-size: 0.8rem;
    }
    /* Modal styles for preview */
    .modal-preview {
        max-width: 90%;
    }
    .modal-preview .modal-body {
        height: 70vh;
    }
    .modal-preview iframe {
        width: 100%;
        height: 100%;
        border: none;
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
<div class="sidebar d-flex flex-column">
    <div class="logo d-flex align-items-center">
        <img src="{{ asset('assets/images/logo.png') }}" alt="PG Rajawali I" class="img-fluid" style="max-width: 100%; padding: 10px;">
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
        <a href="{{ route('dashboard.analisis-produk') }}" class="nav-link">
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
        <a href="{{ route('dashboard.laporan') }}" class="nav-link active">
            <div class="d-flex align-items-center">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <i class="fas fa-file-alt text-primary"></i>
                </div>
                <span>Laporan</span>
            </div>
        </a>
    </div>
    <!-- Logout link removed from sidebar -->
</div>

<div class="main-content">
    <nav class="navbar navbar-expand-lg navbar-light mb-4">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <select class="form-select form-select-sm me-3">
                    <option selected>Tanggal</option>
                    <option value="today">Hari Ini</option>
                    <option value="yesterday">Kemarin</option>
                    <option value="last_week">Minggu Lalu</option>
                    <option value="last_month">Bulan Lalu</option>
                </select>
            </div>
            <div class="d-flex align-items-center">
                <div class="search-bar me-3">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="form-control" placeholder="Cari Laporan">
                </div>
                <!-- Profile dropdown menu -->
                <div class="dropdown profile-dropdown">
                    <div class="d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="me-2">Halo, {{ Auth::user()->name }}</span>
                        <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user me-2"></i> Profil</a></li>
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

    <div class="content-area">
        <div class="report-card">
            <div class="report-card-header">
                <h5 class="report-card-title">Laporan</h5>
                <div class="report-actions">
                    <button class="btn btn-primary" id="downloadAllReports">Download Semua Laporan</button>
                </div>
            </div>
            <div class="report-table-container">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jenis Laporan</th>
                            <th>Tanggal Dibuat</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Laporan Produksi Harian</td>
                            <td>14 April 2025</td>
                            <td>Selesai</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-outline-primary preview-btn" data-report="1">
                                        <i class="fas fa-eye"></i> Preview
                                    </button>
                                    <a href="#" class="btn btn-sm btn-outline-success download-btn" data-report="1">
                                        <i class="fas fa-download"></i> Unduh
                                    </a>
                                    <button class="btn btn-sm btn-outline-secondary print-btn" data-report="1">
                                        <i class="fas fa-print"></i> Cetak
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Laporan Analisis Produk Mingguan</td>
                            <td>12 April 2025</td>
                            <td>Selesai</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-outline-primary preview-btn" data-report="2">
                                        <i class="fas fa-eye"></i> Preview
                                    </button>
                                    <a href="#" class="btn btn-sm btn-outline-success download-btn" data-report="2">
                                        <i class="fas fa-download"></i> Unduh
                                    </a>
                                    <button class="btn btn-sm btn-outline-secondary print-btn" data-report="2">
                                        <i class="fas fa-print"></i> Cetak
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Laporan Stok Barang Bulanan</td>
                            <td>10 April 2025</td>
                            <td>Selesai</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-outline-primary preview-btn" data-report="3">
                                        <i class="fas fa-eye"></i> Preview
                                    </button>
                                    <a href="#" class="btn btn-sm btn-outline-success download-btn" data-report="3">
                                        <i class="fas fa-download"></i> Unduh
                                    </a>
                                    <button class="btn btn-sm btn-outline-secondary print-btn" data-report="3">
                                        <i class="fas fa-print"></i> Cetak
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-center no-data">Tidak ada data laporan yang tersedia.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-preview">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Preview Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe id="previewFrame" src=""></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="printFromPreview">Cetak</button>
                <button type="button" class="btn btn-success" id="downloadFromPreview">Unduh</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navLinks = document.querySelectorAll('.sidebar .nav-link');
        const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
        
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Preview button functionality
        document.querySelectorAll('.preview-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const reportId = this.getAttribute('data-report');
                // In a real application, you would fetch the report content here
                // For demo purposes, we'll use a placeholder
                const reportContent = `<h1>Preview Laporan #${reportId}</h1><p>Ini adalah konten preview untuk laporan ${reportId}.</p>`;
                
                const previewFrame = document.getElementById('previewFrame');
                previewFrame.srcdoc = reportContent;
                
                // Set the modal title
                document.getElementById('previewModalLabel').textContent = `Preview Laporan #${reportId}`;
                
                // Set data attributes for print and download from preview
                previewFrame.setAttribute('data-report-id', reportId);
                
                previewModal.show();
            });
        });

        // Download button functionality
        document.querySelectorAll('.download-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const reportId = this.getAttribute('data-report');
                // In a real application, you would initiate a download here
                alert(`Mengunduh laporan #${reportId}...`);
                // Example: window.location.href = `/reports/${reportId}/download`;
            });
        });

        // Print button functionality
        document.querySelectorAll('.print-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const reportId = this.getAttribute('data-report');
                // In a real application, you would open a print dialog for the report
                alert(`Mencetak laporan #${reportId}...`);
                // Example: window.open(`/reports/${reportId}/print`, '_blank');
            });
        });

        // Download all reports button
        document.getElementById('downloadAllReports').addEventListener('click', function() {
            alert('Mengunduh semua laporan dalam format ZIP...');
            // In a real application, you would initiate a bulk download
            // Example: window.location.href = '/reports/download-all';
        });

        // Print from preview
        document.getElementById('printFromPreview').addEventListener('click', function() {
            const reportId = document.getElementById('previewFrame').getAttribute('data-report-id');
            alert(`Mencetak laporan #${reportId} dari preview...`);
            // In a real application, you would print the iframe content
            // Example: document.getElementById('previewFrame').contentWindow.print();
        });

        // Download from preview
        document.getElementById('downloadFromPreview').addEventListener('click', function() {
            const reportId = document.getElementById('previewFrame').getAttribute('data-report-id');
            alert(`Mengunduh laporan #${reportId} dari preview...`);
            // In a real application, you would initiate download
            // Example: window.location.href = `/reports/${reportId}/download`;
        });
    });
</script>
@endsection