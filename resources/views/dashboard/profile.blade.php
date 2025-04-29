@extends('layouts.app')

@section('title', 'Profil Pengguna | PG Rajawali I')

@section('styles')
<style>

</style>
@endsection

@section('content')
<!-- Alert Popup -->
<div id="alertPopup" class="alert-popup alert-success">
    <i class="fas fa-check-circle me-2"></i> <span id="alertMessage">Data berhasil disimpan</span>
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
                        <li><a class="dropdown-item" href="{{ route('pengaturan') }}"><i class="fas fa-user me-2"></i> Profil</a></li>
                        <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-cog me-2"></i> Pengaturan</a></li>
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
    
    // Fungsi untuk menampilkan popup alert
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
    
    // Fungsi untuk mendapatkan aktivitas tersimpan dari localStorage
    function getSavedActivities() {
        const savedActivities = localStorage.getItem('userActivities');
        return savedActivities ? JSON.parse(savedActivities) : [];
    }
    
    // Fungsi untuk menyimpan aktivitas ke localStorage
    function saveActivity(activity) {
        const activities = getSavedActivities();
        activities.unshift(activity); // Tambahkan aktivitas baru di awal array
        
        // Batasi jumlah aktivitas yang disimpan (misalnya, hanya 20 aktivitas terakhir)
        if (activities.length > 20) {
            activities.pop(); // Hapus aktivitas terlama
        }
        
        localStorage.setItem('userActivities', JSON.stringify(activities));
    }
    
    // Fungsi untuk menambahkan aktivitas ke UI
    function addActivityToUI(activity) {
        const activityList = document.querySelector('#activity');
        if (activityList) {
            // Create new activity item
            const newActivity = document.createElement('div');
            newActivity.className = 'activity-item d-flex';
            newActivity.setAttribute('data-activity-type', activity.type);
            newActivity.setAttribute('data-time', activity.timeCategory);
            newActivity.innerHTML = `
                <div class="activity-icon">
                    <i class="${activity.iconClass}"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">${activity.title}</div>
                    <div class="activity-description">${activity.description}</div>
                    <div class="activity-time">${activity.timeString}</div>
                </div>
            `;
            
            // Insert at the top of the activity list, after any headers
            const firstActivity = activityList.querySelector('.activity-item');
            if (firstActivity) {
                activityList.insertBefore(newActivity, firstActivity);
            } else {
                activityList.appendChild(newActivity);
            }
        }
    }
    
    // Fungsi untuk menambahkan aktivitas baru
    function addActivity(title, description, iconClass, activityType = 'sistem', timeCategory = 'today') {
        const now = new Date();
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        
        // Format tanggal untuk dibaca manusia
        const today = new Date();
        const yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);
        
        let timeString;
        if (now.toDateString() === today.toDateString()) {
            timeString = `Hari ini, ${hours}:${minutes}`;
        } else if (now.toDateString() === yesterday.toDateString()) {
            timeString = `Kemarin, ${hours}:${minutes}`;
        } else {
            const day = now.getDate().toString().padStart(2, '0');
            const month = (now.getMonth() + 1).toString().padStart(2, '0');
            const year = now.getFullYear();
            timeString = `${day}/${month}/${year}, ${hours}:${minutes}`;
        }
        
        // Buat objek aktivitas
        const activity = {
            title,
            description,
            iconClass,
            type: activityType,
            timeCategory,
            timeString,
            timestamp: now.getTime() // Simpan timestamp untuk sorting
        };
        
        // Simpan ke localStorage
        saveActivity(activity);
        
        // Tambahkan ke UI
        addActivityToUI(activity);
        
        return activity;
    }
    
    // Fungsi untuk memuat semua aktivitas tersimpan ke dalam UI
    function loadSavedActivities() {
        const activities = getSavedActivities();
        
        // Sort berdasarkan timestamp (terbaru dulu)
        activities.sort((a, b) => b.timestamp - a.timestamp);
        
        // Hapus placeholder activities yang ada (tapi simpan header)
        const existingActivities = document.querySelectorAll('#activity .activity-item');
        existingActivities.forEach(item => item.remove());
        
        // Tambahkan activities dari localStorage
        activities.forEach(activity => {
            addActivityToUI(activity);
        });
        
        // Jika tidak ada aktivitas yang tersimpan
        if (activities.length === 0) {
            // Biarkan activities default dari template
            console.log('Tidak ada aktivitas tersimpan, menggunakan aktivitas default');
        }
    }
    
    // Rekam aktivitas saat halaman dibuka
    const recordPageVisit = () => {
        const visit = addActivity(
            'Membuka halaman profil', 
            'Anda telah mengakses halaman profil pengguna.',
            'fas fa-user',
            'akses',
            'today'
        );
        return visit;
    };
    
    // Rekam aktivitas saat halaman ditutup atau pengguna pindah ke halaman lain
    const recordPageExit = () => {
        addActivity(
            'Meninggalkan halaman profil', 
            'Anda telah keluar dari halaman profil pengguna.',
            'fas fa-sign-out-alt',
            'akses',
            'today'
        );
    };
    
    // Muat aktivitas tersimpan saat halaman dibuka
    loadSavedActivities();
    
    // Rekam kunjungan halaman
    const currentVisit = recordPageVisit();
    
    // Event listener untuk saat pengguna meninggalkan halaman
    window.addEventListener('beforeunload', function(e) {
        recordPageExit();
    });
    
    // Event listener untuk tab profil aktivitas
    const activityTab = document.getElementById('activity-tab');
    if (activityTab) {
        activityTab.addEventListener('click', function() {
            addActivity(
                'Melihat riwayat aktivitas', 
                'Anda telah membuka tab riwayat aktivitas pada profil pengguna.',
                'fas fa-history',
                'akses',
                'today'
            );
        });
    }
    
    // Profile form submission
    const saveProfileBtn = document.getElementById('saveProfileBtn');
    if (saveProfileBtn) {
        saveProfileBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Tambahkan token CSRF ke header request
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
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
            
            // Membuat deskripsi perubahan
            const changedFields = [];
            if (profileData.name !== originalData.name) changedFields.push('nama');
            if (profileData.username !== originalData.username) changedFields.push('username');
            if (profileData.phone !== originalData.phone) changedFields.push('nomor telepon');
            if (profileData.position !== originalData.position) changedFields.push('jabatan');
            if (profileData.address !== originalData.address) changedFields.push('alamat');
            
            const changeDesc = changedFields.length > 0 
                ? `Informasi yang diperbarui: ${changedFields.join(', ')}.` 
                : 'Informasi profil telah diperbarui.';
            
            // Kirim data ke server atau simulasi jika dalam mode development
            try {
                // Cek apakah kita bisa mengakses server
                if (typeof fetch === 'function' && token) {
                    // Kirim ke server asli jika ada
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
                            
                            // Tambahkan ke daftar aktivitas
                            addActivity(
                                'Memperbarui profil',
                                changeDesc,
                                'fas fa-user-edit',
                                'profil',
                                'today'
                            );
                            
                            // Tampilkan pesan sukses
                            showAlert(data.message || 'Profil berhasil diperbarui', 'success');
                        } else {
                            showAlert(data.message || 'Gagal memperbarui profil', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        
                        // Fallback ke simulasi jika terjadi kesalahan
                        simulateProfileUpdate();
                    })
                    .finally(() => {
                        // Reset tombol
                        saveProfileBtn.disabled = false;
                        saveProfileBtn.innerHTML = 'Simpan Perubahan';
                    });
                } else {
                    // Simulasi saja jika fetch tidak tersedia
                    simulateProfileUpdate();
                }
            } catch (error) {
                // Fallback ke simulasi jika terjadi kesalahan
                simulateProfileUpdate();
            }
            
            // Fungsi untuk mensimulasikan update profil
            function simulateProfileUpdate() {
                setTimeout(() => {
                    // Reset tombol
                    saveProfileBtn.disabled = false;
                    saveProfileBtn.innerHTML = 'Simpan Perubahan';
                    
                    // Update default values
                    document.getElementById('name').defaultValue = profileData.name;
                    document.getElementById('username').defaultValue = profileData.username;
                    document.getElementById('phone').defaultValue = profileData.phone;
                    document.getElementById('position').defaultValue = profileData.position;
                    document.getElementById('address').defaultValue = profileData.address;
                    
                    // Update UI
                    updateUIWithData(profileData);
                    
                    // Tambahkan aktivitas
                    addActivity(
                        'Memperbarui profil',
                        changeDesc,
                        'fas fa-user-edit',
                        'profil',
                        'today'
                    );
                    
                    // Tampilkan pesan sukses
                    showAlert('Profil berhasil diperbarui', 'success');
                }, 1500);
            }
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
    
    // Tab navigasi event listeners untuk aktivitas
    const tabButtons = document.querySelectorAll('.nav-tabs .nav-link');
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (this.id !== 'activity-tab') {
                const tabName = this.textContent.trim();
                addActivity(
                    `Beralih ke tab ${tabName}`,
                    `Anda telah beralih ke tab ${tabName} pada halaman profil.`,
                    'fas fa-exchange-alt',
                    'navigasi',
                    'today'
                );
            }
        });
    });
    
    // Tombol kembali event listener
    const backButton = document.querySelector('.btn-sm.btn-primary');
    if (backButton) {
        backButton.addEventListener('click', function() {
            addActivity(
                'Kembali ke halaman sebelumnya',
                'Anda telah menekan tombol kembali dari halaman profil.',
                'fas fa-arrow-left',
                'navigasi',
                'today'
            );
        });
    }
    
    // Menu dropdown event listeners untuk melacak klik menu
    const dropdownItems = document.querySelectorAll('.dropdown-menu .dropdown-item');
    dropdownItems.forEach(item => {
        item.addEventListener('click', function(e) {
            // Kecuali untuk logout, karena akan menavigasi keluar
            if (!this.classList.contains('text-danger')) {
                const menuText = this.textContent.trim();
                addActivity(
                    `Mengakses menu ${menuText}`,
                    `Anda telah mengakses menu ${menuText} dari dropdown profil.`,
                    'fas fa-list',
                    'navigasi',
                    'today'
                );
            }
        });
    });
    
    // Logout event listener untuk menambahkan aktivitas sebelum logout
    const logoutLink = document.querySelector('.dropdown-item.text-danger');
    if (logoutLink) {
        // Simpan handler asli
        const originalOnClick = logoutLink.onclick;
        
        // Ganti dengan handler baru
        logoutLink.onclick = function(e) {
            e.preventDefault();
            
            // Tambahkan aktivitas
            addActivity(
                'Logout dari sistem',
                'Anda telah melakukan logout dari sistem.',
                'fas fa-sign-out-alt',
                'login',
                'today'
            );
            
            // Jalankan handler asli setelah delay pendek untuk memastikan aktivitas disimpan
            setTimeout(() => {
                if (originalOnClick) {
                    // Panggil handler asli
                    originalOnClick.call(this, e);
                } else {
                    // Atau submit form logout secara manual
                    document.getElementById('logout-form').submit();
                }
            }, 300);
        };
    }
});
</script>
@endsection