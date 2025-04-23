@extends('layouts.app')

@section('title', 'Profil Pengguna | PG Rajawali I')

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
    
    /* Profile Page Specific Styles */
    .profile-header {
        background-color: white;
        border-radius: 10px;
        padding: 30px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    .profile-avatar {
        width: 100px;
        height: 100px;
        background-color: #004a94;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        font-weight: bold;
        margin-right: 25px;
    }
    .profile-info h2 {
        font-size: 24px;
        margin-bottom: 5px;
    }
    .profile-info p {
        color: #666;
        margin-bottom: 3px;
    }
    .profile-content {
        background-color: white;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    .nav-tabs {
        border-bottom: 1px solid #ddd;
        margin-bottom: 20px;
    }
    .nav-tabs .nav-link {
        color: #666;
        border: none;
        padding: 10px 20px;
        border-bottom: 3px solid transparent;
        transition: all 0.3s;
    }
    .nav-tabs .nav-link:hover {
        border-bottom: 3px solid #ccc;
    }
    .nav-tabs .nav-link.active {
        color: #004a94;
        border-bottom: 3px solid #004a94;
        font-weight: bold;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-label {
        font-weight: 500;
        margin-bottom: 8px;
        display: block;
    }
    .form-control {
        border-radius: 5px;
        border: 1px solid #ddd;
        padding: 10px 15px;
    }
    .btn-save {
        background-color: #004a94;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .btn-save:hover {
        background-color: #003366;
    }
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 24px;
    }
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .toggle-slider {
        background-color: #004a94;
    }
    input:checked + .toggle-slider:before {
        transform: translateX(26px);
    }
    .activity-item {
        padding: 15px 0;
        border-bottom: 1px solid #eee;
    }
    .activity-item:last-child {
        border-bottom: none;
    }
    .activity-icon {
        width: 40px;
        height: 40px;
        background-color: #e6f0fa;
        color: #004a94;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }
    .activity-content {
        flex: 1;
    }
    .activity-title {
        font-weight: 500;
        margin-bottom: 3px;
    }
    .activity-time {
        color: #999;
        font-size: 0.8rem;
    }
    
    /* Pop-up Alert Styles */
    .alert-popup {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        color: white;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        z-index: 9999;
        display: none;
        animation: slideIn 0.5s forwards;
    }
    
    .alert-success {
        background-color: #4CAF50;
    }
    
    .alert-warning {
        background-color: #ff9800;
    }
    
    .alert-error {
        background-color: #f44336;
    }
    
    @keyframes slideIn {
        0% {
            transform: translateX(100%);
            opacity: 0;
        }
        100% {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes fadeOut {
        0% {
            opacity: 1;
        }
        100% {
            opacity: 0;
        }
    }
    
    .alert-popup.show {
        display: block;
    }
    
    .alert-popup.hide {
        animation: fadeOut 0.5s forwards;
    }
    
    /* Removed filter dropdown styles */
    
    .activity-hidden {
        display: none !important;
    }
    
    /* Style untuk debug label */
    .debug-label {
        background-color: #fffde7;
        padding: 5px;
        margin: 5px 0;
        font-size: 12px;
        font-family: monospace;
        border-radius: 3px;
        border-left: 3px solid #ffc107;
    }
</style>
@endsection

@section('content')
<!-- Alert Popup -->
<div id="alertPopup" class="alert-popup alert-success">
    <i class="fas fa-check-circle me-2"></i> <span id="alertMessage">Data berhasil disimpan</span>
</div>

<!-- Sidebar -->
<div class="sidebar d-flex flex-column">
    <div class="logo d-flex align-items-center justify-content-center">
        <a href="{{ route('dashboard') }}" title="Kembali ke Dashboard">
            <img src="{{ asset('assets/images/logo.png') }}" alt="PG Rajawali I">
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
        <a href="{{ route('dashboard.laporan') }}" class="nav-link">
            <div class="d-flex align-items-center">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <i class="fas fa-file-alt text-primary"></i>
                </div>
                <span>Laporan</span>
            </div>
        </a>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light mb-4">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <h5 class="mb-0">Profil Pengguna</h5>
            </div>
            <div class="d-flex align-items-center">
                <div class="search-bar me-3">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="form-control" placeholder="Search">
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

    <!-- Profile Header -->
    <div class="profile-header d-flex">
        <div class="profile-avatar">
            {{ substr(Auth::user()->name, 0, 1) }}
        </div>
        <div class="profile-info d-flex flex-column justify-content-center">
            <h2>{{ Auth::user()->name }}</h2>
            <p><i class="fas fa-envelope me-2"></i>{{ Auth::user()->username ?? 'admin' }}</p>
            <p><i class="fas fa-id-badge me-2"></i>Administrator</p>
        </div>
    </div>

    <!-- Profile Content -->
    <div class="profile-content">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <ul class="nav nav-tabs mb-0" id="profileTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="true">Informasi Akun</button>
                </li>
                <!-- Tab keamanan dihapus -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button" role="tab" aria-controls="activity" aria-selected="false">Aktivitas</button>
                </li>
            </ul>
            <button class="btn btn-sm btn-primary" onclick="window.history.back()">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </button>
        </div>

        <div class="tab-content" id="profileTabsContent">
            <!-- Informasi Akun Tab -->
            <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                <form id="profileForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="name" value="{{ Auth::user()->name }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username" class="form-label">username</label>
                                <input type="username" class="form-control" id="username" value="{{ Auth::user()->username ?? 'username@example.com' }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control" id="phone" value="{{ Auth::user()->phone ?? '+62 812 3456 7890' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="position" class="form-label">Jabatan</label>
                                <input type="text" class="form-control" id="position" value="{{ Auth::user()->position ?? 'Administrator' }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="address" class="form-label">Alamat</label>
                                <textarea class="form-control" id="address" rows="3">{{ Auth::user()->address ?? 'Jl. Raya Timur No. 123, Surabaya, Jawa Timur' }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-end">
                        <button type="button" id="saveProfileBtn" class="btn btn-save">Simpan Perubahan</button>
                    </div>
                </form>
            </div>

            <!-- Tab keamanan dihapus -->

            <!-- Aktivitas Tab -->
            <div class="tab-pane fade" id="activity" role="tabpanel" aria-labelledby="activity-tab">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Aktivitas Terbaru</h6>
                </div>
                
                <div class="activity-item d-flex" data-activity-type="laporan" data-time="today">
                    <div class="activity-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Mengunduh laporan produksi harian</div>
                        <div class="activity-description">Anda telah mengunduh laporan produksi harian untuk tanggal 14 April 2025.</div>
                        <div class="activity-time">Hari ini, 10:45</div>
                    </div>
                </div>
                
                <div class="activity-item d-flex" data-activity-type="login" data-time="today">
                    <div class="activity-icon">
                        <i class="fas fa-sign-in-alt"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Login ke sistem</div>
                        <div class="activity-description">Anda telah login ke sistem dari IP 192.168.1.100 menggunakan Chrome di Windows.</div>
                        <div class="activity-time">Hari ini, 08:00</div>
                    </div>
                </div>
                
                <div class="activity-item d-flex" data-activity-type="edit" data-time="yesterday">
                    <div class="activity-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Mengubah data kontrak</div>
                        <div class="activity-description">Anda telah mengubah data kontrak #23456 dari kategori "Gula Rafinasi" ke "Gula Pasir".</div>
                        <div class="activity-time">Kemarin, 14:20</div>
                    </div>
                </div>
                
                <div class="activity-item d-flex" data-activity-type="akses" data-time="week">
                    <div class="activity-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Mengakses dashboard analisis produk</div>
                        <div class="activity-description">Anda telah mengakses dashboard analisis produk dengan filter periode 1 Januari - 14 April 2025.</div>
                        <div class="activity-time">12 April 2025, 16:35</div>
                    </div>
                </div>
                
                <div class="activity-item d-flex" data-activity-type="profil" data-time="week">
                    <div class="activity-icon">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Memperbarui profil</div>
                        <div class="activity-description">Anda telah memperbarui informasi profil pengguna.</div>
                        <div class="activity-time">10 April 2025, 09:15</div>
                    </div>
                </div>
                
                <!-- Pesan saat tidak ada aktivitas yang sesuai filter -->
                <div id="noActivitiesMessage" class="text-center py-4 d-none">
                    <i class="fas fa-info-circle text-muted mb-2" style="font-size: 2rem;"></i>
                    <p class="text-muted">Tidak ada aktivitas yang sesuai dengan filter yang dipilih.</p>
                </div>
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
        // Sidebar navigation activation
        const navLinks = document.querySelectorAll('.sidebar .nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });
        
        // Function to show popup alert
        function showAlert(message = 'Data berhasil disimpan', type = 'success') {
            const alertPopup = document.getElementById('alertPopup');
            
            // Update alert message and icon based on type
            if (type === 'success') {
                alertPopup.className = 'alert-popup alert-success';
                alertPopup.innerHTML = `<i class="fas fa-check-circle me-2"></i> ${message}`;
            } else if (type === 'warning') {
                alertPopup.className = 'alert-popup alert-warning';
                alertPopup.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i> ${message}`;
            } else if (type === 'error') {
                alertPopup.className = 'alert-popup alert-error';
                alertPopup.innerHTML = `<i class="fas fa-times-circle me-2"></i> ${message}`;
            }
            
            // Show alert
            alertPopup.classList.add('show');
            
            // Hide alert after 3 seconds
            setTimeout(function() {
                alertPopup.classList.add('hide');
                setTimeout(function() {
                    alertPopup.classList.remove('show');
                    alertPopup.classList.remove('hide');
                }, 500);
            }, 3000);
        }
        
        // Profile form submission
        const saveProfileBtn = document.getElementById('saveProfileBtn');
        if (saveProfileBtn) {
            saveProfileBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Tambahkan token CSRF ke header request
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                // Dapatkan data original untuk perbandingan
                const originalData = {
                    name: document.getElementById('name').defaultValue || document.getElementById('name').getAttribute('value'),
                    username: document.getElementById('username').defaultValue || document.getElementById('username').getAttribute('value'),
                    phone: document.getElementById('phone').defaultValue || document.getElementById('phone').getAttribute('value'),
                    position: document.getElementById('position').defaultValue || document.getElementById('position').getAttribute('value'),
                    address: document.getElementById('address').defaultValue || document.getElementById('address').textContent.trim()
                };
                
                // Dapatkan data terbaru dari form
                const profileData = {
                    name: document.getElementById('name').value,
                    username: document.getElementById('username').value,
                    phone: document.getElementById('phone').value,
                    position: document.getElementById('position').value,
                    address: document.getElementById('address').value
                };
                
                // Periksa apakah ada perubahan data
                const hasChanges = Object.keys(profileData).some(key => 
                    profileData[key] !== originalData[key]
                );
                
                if (!hasChanges) {
                    showAlert('Tidak ada perubahan yang dilakukan pada data profil', 'warning');
                    return;
                }
                
                // Basic validation
                if (!profileData.name) {
                    showAlert('Nama tidak boleh kosong', 'error');
                    return;
                }
                
                if (!profileData.username) {
                    showAlert('Username tidak boleh kosong', 'error');
                    return;
                }
                
                // Tampilkan loading state
                saveProfileBtn.disabled = true;
                saveProfileBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
                
                // Kirim data ke server
                fetch('/profile/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify(profileData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update UI dengan data yang diterima dari server
                        updateUIWithData(data.user);
                        
                        // Update default values untuk perbandingan selanjutnya
                        document.getElementById('name').defaultValue = data.user.name;
                        document.getElementById('username').defaultValue = data.user.username;
                        document.getElementById('phone').defaultValue = data.user.phone;
                        document.getElementById('position').defaultValue = data.user.position;
                        document.getElementById('address').defaultValue = data.user.address;
                        
                        // Tampilkan pesan sukses
                        showAlert(data.message || 'Profil berhasil diperbarui', 'success');
                        
                        // Tambahkan ke daftar aktivitas
                        addActivityItem('Memperbarui profil', 'Anda telah memperbarui informasi profil pengguna.', 'fas fa-user-edit', 'profil', 'today');
                    } else {
                        showAlert(data.message || 'Gagal memperbarui profil', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Terjadi kesalahan saat menyimpan data', 'error');
                })
                .finally(() => {
                    // Reset tombol
                    saveProfileBtn.disabled = false;
                    saveProfileBtn.innerHTML = 'Simpan Perubahan';
                });
            });
        }
        
        // Function to update UI with user data
        function updateUIWithData(user) {
            // Update header name
            const nameHeader = document.querySelector('.profile-info h2');
            if (nameHeader) {
                nameHeader.textContent = user.name;
            }
            
            // Update avatar initial
            const avatarInitial = user.name.charAt(0);
            const profileAvatar = document.querySelector('.profile-avatar');
            if (profileAvatar) {
                profileAvatar.textContent = avatarInitial;
            }
            
            // Update user avatar in navbar
            const userAvatar = document.querySelector('.user-avatar');
            if (userAvatar) {
                userAvatar.textContent = avatarInitial;
            }
            
            // Update dropdown name
            const dropdownName = document.querySelector('.profile-dropdown .me-2');
            if (dropdownName) {
                dropdownName.textContent = 'Halo, ' + user.name;
            }
            
            // Update username info
            const emailInfo = document.querySelector('.profile-info p:nth-child(2)');
            if (emailInfo) {
                emailInfo.innerHTML = `<i class="fas fa-envelope me-2"></i>${user.username}`;
            }
        }
        
        // Function to add activity item
        function addActivityItem(title, description, iconClass, activityType = 'profil', timeCategory = 'today') {
            const activityList = document.querySelector('#activity');
            if (activityList) {
                const now = new Date();
                const hours = now.getHours().toString().padStart(2, '0');
                const minutes = now.getMinutes().toString().padStart(2, '0');
                const timeString = `Hari ini, ${hours}:${minutes}`;
                
                // Create new activity item
                const newActivity = document.createElement('div');
                newActivity.className = 'activity-item d-flex';
                newActivity.setAttribute('data-activity-type', activityType);
                newActivity.setAttribute('data-time', timeCategory);
                newActivity.innerHTML = `
                    <div class="activity-icon">
                        <i class="${iconClass}"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">${title}</div>
                        <div class="activity-description">${description}</div>
                        <div class="activity-time">${timeString}</div>
                    </div>
                `;
                
                // Insert at the top of the activity list
                const firstActivity = activityList.querySelector('.activity-item');
                if (firstActivity) {
                    activityList.insertBefore(newActivity, firstActivity);
                } else {
                    activityList.appendChild(newActivity);
                }
            }
        }
        
        // Removed the filter initialization and related functions
    });
</script>
@endsection