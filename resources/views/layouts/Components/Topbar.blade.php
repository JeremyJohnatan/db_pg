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
        z-index: 1020; /* Nilai z-index lebih rendah dari navbar */
    }
    .sidebar .logo {
        padding: 15px;
        font-weight: bold;
        font-size: 1.2rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        background-color: #004a94;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    /* Style untuk logo agar terlihat dapat diklik */
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
        padding-top: 80px; /* Sesuaikan dengan tinggi navbar */
        padding-left: 20px;
        padding-right: 20px;
        padding-bottom: 20px;
    }
    .navbar {
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        position: fixed; /* Membuat navbar tetap berada di posisinya */
        top: 0; /* Posisi di bagian atas */
        right: 0; /* Posisi di bagian kanan */
        left: 250px; /* Sesuaikan dengan lebar sidebar (250px) */
        z-index: 1030; /* Nilai z-index yang lebih tinggi untuk memastikan navbar di atas semua konten */
        width: calc(100% - 250px); /* Lebar navbar harus dikurangi lebar sidebar */
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
</style>
@endsection

<!-- resources/views/components/topbar.blade.php -->
<nav class="navbar navbar-expand-lg navbar-light mb-4">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <div class="input-group me-3">
                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                <input type="date" class="form-control" id="tanggal-mulai" name="tanggal_mulai" value="{{ $tanggalMulai ?? '' }}">
            </div>
            <div class="input-group me-3">
                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                <input type="date" class="form-control" id="tanggal-akhir" name="tanggal_akhir" value="{{ $tanggalAkhir ?? '' }}">
            </div>
            <button class="btn btn-primary btn-sm" id="filter-tanggal">Filter</button>
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