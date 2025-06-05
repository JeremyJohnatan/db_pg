@extends('layouts.app')

@section('title', 'Pengaturan Akun | PG Rajawali I')

@section('styles')
<style>
:root {
  --primary: #004a94;
  --primary-light: rgba(0, 74, 148, 0.1);
  --secondary: #f5f7fb;
  --button-bg: #004a94;
  --button-text: #ffffff;
  --success: #28a745;
  --info: #004a94;
  --info-light: rgba(23, 162, 184, 0.1);
  --danger: #dc3545;
}

body.light {
    --bg-color: var(--secondary);
    --text-color: #000000;
    --box-bg: #f8f9fc;
    --box-border: #e0e0e0;
}

body.dark {
    --bg-color: #fffbe6;
    --text-color: #000000;
    --box-bg: #fffdf2;
    --box-border: #e0d9b9;
}

body {
    background-color: var(--bg-color);
    color: var(--text-color);
}

.navbar {
    background-color: var(--box-bg);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    position: fixed;
    top: 0;
    right: 0;
    left: 250px;
    z-index: 1030;
    width: calc(100% - 250px);
    padding: 5px 0px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}


.user-avatar {
    width: 32px;
    height: 32px;
    background-color: var(--primary);
    color: var(--button-text);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-left: 8px;
}

.content-box {
    background: var(--box-bg);
    border: 1px solid var(--box-border);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.shortcut-btn {
    background-color: var(--button-bg);
    color: var(--button-text);
    border-radius: 8px !important;
    transition: all 0.3s ease;
}

.shortcut-btn:hover {
    opacity: 0.9;
    transform: translateY(-2px);
}

/* Simple Date Time Widget */

.simple-datetime-widget h6 {
    color: var(--primary);
    font-weight: 600;
    margin-bottom: 10px;
}

.datetime-display .current-date {
    font-size: 0.85rem;
    color: var(--text-color);
    margin-bottom: 5px;
    font-weight: 500;
}

.datetime-display {
    text-align: center; /* agar tetap di tengah */
    white-space: nowrap; /* cegah pindah baris */
}

.datetime-display .current-time {
    font-size: 1.4rem;
    font-weight: bold;
    color: var(--primary);
    font-family: 'Courier New', monospace;
}

.datetime-display {
    justify-content: center; /* atau space-between jika ingin merenggang */
    align-items: center;
    gap: 0px; /* ini yang bisa kamu kecilkan agar lebih dekat */
}

/* Card Container Keamanan */
#security-section {
    border-left: 3px solid var(--primary);
    position: relative;
    overflow: hidden;
}

#security-section::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(0,74,148,0.03) 0%, transparent 100%);
    z-index: -1;
}

.info-card {
    background-color: var(--info-light);
    border-left: 4px solid var(--info);
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    animation: fadeIn 0.5s ease;
}

.info-card h6 {
    color: var(--info);
    font-weight: 600;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
}

.info-card p {
    font-size: 0.9rem;
    line-height: 1.5;
    margin-bottom: 0;
}

.security-feature-item {
    display: flex;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px dashed var(--box-border);
}

.security-feature-item:last-child {
    border-bottom: none;
}

.security-feature-icon {
    width: 32px;
    height: 32px;
    background-color: var(--primary-light);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    color: var(--primary);
}

.security-feature-text h6 {
    font-weight: 600;
    margin-bottom: 3px;
    color: var(--text-color);
}

.security-feature-text p {
    font-size: 0.85rem;
    color: var(--text-color);
    opacity: 0.7;
    margin-bottom: 0;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Responsive */
@media (max-width: 768px) {
    .content-box .d-flex {
        flex-direction: column;
        gap: 15px;
    }
    
    .simple-datetime-widget {
        align-self: stretch;
    }
    
    .navbar {
        right: 0;
        left: 0;
        width: 100%;
    }
}

@media (max-width: 576px) {
    .security-feature-item {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .security-feature-icon {
        margin-bottom: 10px;
    }
}
</style>
@endsection

@section('content')
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <h5 class="mb-0">Pengaturan</h5>
        <div class="d-flex align-items-center">
            <div class="dropdown">
                <button class="btn border-0 bg-transparent d-flex align-items-center"
                        data-bs-toggle="dropdown" aria-expanded="false" id="dropdownMenuButton">
                    <span class="me-2">Halo, {{ Auth::user()->name }}</span>
                    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name,0,1)) }}</div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user me-2"></i>Profil</a></li>
                    <li><a class="dropdown-item" href="{{ route('pengaturan') }}"><i class="fas fa-cog me-2"></i>Pengaturan</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i>Log Out
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

<div class="main-content-container">
    <div class="left-content">
        {{-- Kotak Pintasan --}}
        <div class="content-box">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h5 class="mb-3">Pintasan</h5>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('profile') }}" class="btn btn-light border shadow-sm px-4 py-2 rounded-3 shortcut-btn">
                            <i class="fas fa-user-edit me-2"></i>Edit Profil
                        </a>
                        <!--<a href="{{ route('dashboard.users') }}" class="btn btn-light border shadow-sm px-4 py-2 rounded-3 shortcut-btn">
                            <i class="fas fa-lock me-2"></i>Keamanan
                        </a>-->
                    </div>
                </div>

                
                
                <!-- Simple Date Time Widget -->
                 
                <div class="simple-datetime-widget">
                    <div class="datetime-display">
                      <h6 class="text-primary mb-2">Tanggal</h6>
                        <div id="currentDate" class="current-date">Loading...</div>
                        <div id="currentTime" class="current-time">00:00:00</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alert keamanan --}}
        <div class="content-box" id="security-section">
            <h5>Keamanan Akun</h5>
            
            <div class="info-card">
                <h6><i class="fas fa-info-circle me-2"></i>Perubahan Password</h6>
                <p>Untuk keamanan sistem, perubahan password hanya dapat dilakukan oleh administrator.</p>
            </div>
            
            <div class="security-feature-item">
                <div class="security-feature-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="security-feature-text">
                    <h6>Verifikasi Email</h6>
                    <p>Email Anda telah terverifikasi</p>
                </div>
            </div>
            
            <div class="security-feature-item">
                <div class="security-feature-icon">
                    <i class="fas fa-laptop"></i>
                </div>
                <div class="security-feature-text">
                    <h6>Sesi Aktif</h6>
                    <p>Anda login dari perangkat ini</p>
                </div>
            </div>
        </div>

        {{-- Kotak Catatan --}}
        <div class="content-box">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Catatan Pribadi</h5>
                <button class="btn btn-primary btn-sm" id="add-note">
                    <i class="fas fa-plus me-1"></i>Tambah Catatan
                </button>
            </div>
            <textarea class="form-control" id="personal-notes" rows="3"
                placeholder="Ketik di sini... catatan akan tersimpan otomatis"></textarea>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Update waktu real-time
    function updateTime() {
        const now = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        
        document.getElementById('currentDate').textContent = 
            now.toLocaleDateString('id-ID', options);
        document.getElementById('currentTime').textContent = 
            now.toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit' 
            });
    }

    // Toggle Theme
    const body = document.body;
    const savedTheme = localStorage.getItem('theme');
    
    if (savedTheme === 'dark') {
        body.classList.remove('light');
        body.classList.add('dark');
    } else {
        body.classList.add('light');
    }

    // Catatan Pribadi
    const personalNotes = document.getElementById('personal-notes');
    const addNoteBtn = document.getElementById('add-note');

    // Load catatan yang tersimpan
    if (localStorage.getItem('personalNotes')) {
        personalNotes.value = localStorage.getItem('personalNotes');
    }

    // Auto-save catatan
    personalNotes.addEventListener('input', function() {
        localStorage.setItem('personalNotes', this.value);
    });

    addNoteBtn.addEventListener('click', function() {
        const noteText = prompt('Masukkan catatan pribadi:');
        if (noteText) {
            personalNotes.value = noteText;
            localStorage.setItem('personalNotes', noteText);
        }
    });

    // Inisialisasi
    updateTime();
    
    // Update waktu setiap detik
    setInterval(updateTime, 1000);
});
</script>
@endsection