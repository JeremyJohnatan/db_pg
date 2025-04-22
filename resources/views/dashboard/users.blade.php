@extends('layouts.app')

@section('title', 'User | PG Rajawali I')

@section('styles')
<style>
    .main-content {
        margin-left: 250px;
        padding: 20px;
        background-color: #f8f9fa;
        min-height: 100vh;
    }
    
    .sidebar {
        width: 250px;
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        background-color: #0052b4;
        color: white;
        padding-top: 20px;
        overflow-y: auto;
    }
    
    .sidebar .nav-link {
        color: rgba(255, 255, 255, 0.8);
        padding: 12px 20px;
        display: flex;
        align-items: center;
        transition: all 0.3s;
    }
    
    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        color: white;
        background-color: rgba(255, 255, 255, 0.1);
    }
    
    .sidebar .nav-link i,
    .sidebar .nav-link svg {
        margin-right: 10px;
    }
    
    .sidebar-logo {
        padding: 15px 20px;
        margin-bottom: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .sidebar-logo img {
        max-height: 40px;
    }
    
    .user-table {
        width: 100%;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    
    .user-table thead th {
        background-color: #f5f7fb;
        color: #6c757d;
        border-bottom: 1px solid #dee2e6;
        padding: 12px 15px;
    }
    
    .user-table tbody td {
        padding: 12px 15px;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .search-container {
        position: relative;
        width: 100%;
        max-width: 300px;
    }
    
    .search-container input {
        width: 100%;
        padding: 8px 15px;
        padding-left: 35px;
        border-radius: 20px;
        border: 1px solid #ddd;
        background-color: #f9f9f9;
    }
    
    .search-container .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #aaa;
    }
    
    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .user-info {
        display: flex;
        align-items: center;
    }
    
    .user-info .user-icon {
        margin-right: 8px;
    }
    
    .add-user-btn {
        background-color: #0052b4;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        cursor: pointer;
    }
    
    .add-user-btn:hover {
        background-color: #003d87;
    }
</style>
@endsection

@section('content')
<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('images/logo.png') }}" alt="PG Rajawali I" class="img-fluid">
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="fas fa-home"></i> Home
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard.analisis-produk') }}">
                <i class="fas fa-chart-bar"></i> Analisis Produk
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard.analisis-pabrik') }}">
                <i class="fas fa-industry"></i> Analisis Pabrik
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard.laporan') }}">
                <i class="fas fa-file-alt"></i> Laporan
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('dashboard.users') }}">
                <i class="fas fa-users"></i> User
            </a>
        </li>
    </ul>
    
    <div class="mt-auto" style="margin-top: auto; padding-top: 20px; border-top: 1px solid rgba(255, 255, 255, 0.1);">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-link w-100 text-left border-0 bg-transparent">
                <i class="fas fa-sign-out-alt"></i> Log Out
            </button>
        </form>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <!-- Header Bar -->
    <div class="header-container">
        <h3>User</h3>
        <div class="d-flex align-items-center">
            <div class="search-container me-4">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="searchInput" placeholder="Search">
            </div>
            <div class="user-info">
                <i class="fas fa-user-circle user-icon"></i>
                <span>Halo, {{ Auth::user()->name }}</span>
            </div>
        </div>
    </div>
    
    <!-- User Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover user-table mb-0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Divisi</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->division ?? '-' }}</td>
                                <td>{{ $user->role ?? 'User' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">Tidak ada data pengguna</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Add User Button -->
    <div class="d-flex justify-content-end mt-3">
        <a href="{{ route('users.create') }}" class="add-user-btn">
            Tambah User
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('keyup', function() {
            const filter = searchInput.value.toUpperCase();
            const table = document.querySelector('.user-table');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) { // Start from 1 to skip header
                const nameCell = rows[i].getElementsByTagName('td')[0];
                if (nameCell) {
                    const nameText = nameCell.textContent || nameCell.innerText;
                    if (nameText.toUpperCase().indexOf(filter) > -1) {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
            }
        });
    });
</script>
@endsection