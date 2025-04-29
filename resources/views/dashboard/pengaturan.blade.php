@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('styles')
<style>
    body {
        background-color: #f5f7fb;
        font-family: Arial, sans-serif;
    }

    /* Sidebar */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 250px;
        height: 100%;
        background: #043B82;
        display: flex;
        flex-direction: column;
        padding-top: 20px;
    }

    .sidebar .logo-container {
        text-align: center;
        padding: 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar .logo-container img {
        max-width: 220px;
        height: auto;
    }

    .sidebar .nav-links {
        list-style: none;
        padding: 0;
        margin-top: 20px;
    }

    .sidebar .nav-links li {
        width: 100%;
    }

    .sidebar .nav-links li a {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        color: white;
        text-decoration: none;
        transition: 0.3s;
    }

    .sidebar .nav-links li a.active,
    .sidebar .nav-links li a:hover {
        background: #0056b3;
    }

    .sidebar .nav-links li a i {
        background: white;
        color: #043B82;
        padding: 10px;
        border-radius: 50%;
        margin-right: 15px;
        font-size: 16px;
    }

    .sidebar .nav-links li a span {
        font-size: 16px;
    }

    /* Navbar */
    .navbar {
        position: fixed;
        top: 0;
        left: 250px;
        right: 0;
        height: 60px;
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        padding: 0 20px;
        z-index: 1000;
    }

    .navbar h5 {
        margin: 0;
    }

       .profile-dropdown {
        margin-left: 20px;
    }

    .user-avatar {
        background: #043B82;
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    /* Main Content */
 
</style>
@endsection

@section('content')
<!-- Sidebar -->
<div class="sidebar">
    <div class="logo-container">
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo">
        </a>
    </div>
    <ul class="nav-links">
        <li>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i><span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('dashboard.analisis-produk') }}" class="{{ request()->routeIs('dashboard.analisis-produk') ? 'active' : '' }}">
                <i class="fas fa-box"></i><span>Analisis Produk</span>
            </a>
        </li>
        <li>
            <a href="{{ route('dashboard.analisis-pabrik') }}" class="{{ request()->routeIs('dashboard.analisis-pabrik') ? 'active' : '' }}">
                <i class="fas fa-industry"></i><span>Analisis Pabrik</span>
            </a>
        </li>
        <li>
            <a href="{{ route('dashboard.laporan') }}" class="{{ request()->routeIs('dashboard.laporan') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i><span>Laporan</span>
            </a>
        </li>
        <li>
            <a href="{{ route('dashboard.users') }}" class="{{ request()->routeIs('dashboard.users') ? 'active' : '' }}">
                <i class="fas fa-users"></i><span>Users</span>
            </a>
        </li>
    </ul>
</div>

<!-- Navbar -->
<div class="navbar">
    <h5>Pengaturan</h5>
    <div class="dropdown profile-dropdown">
        <div class="d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
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

<!-- Main Content -->
<div class="main-content">
    <div class="pengaturan-container">
        <h2>Informasi Akun</h2>
        <form action="{{ route('pengaturan.update') }}" method="POST">
            @csrf
            @method('POST')
            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" required>
            </div>
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="{{ auth()->user()->username ?? '' }}" required>
            </div>
            <div class="form-group" style="text-align: center;">
                <button type="submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
