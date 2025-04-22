@extends('layouts.app')

@section('title', 'Tambah User | PG Rajawali I')

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
    
    .form-card {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    
    .btn-primary {
        background-color: #0052b4;
        border-color: #0052b4;
    }
    
    .btn-primary:hover {
        background-color: #003d87;
        border-color: #003d87;
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Tambah User</h3>
        <div class="user-info">
            <i class="fas fa-user-circle me-2"></i>
            <span>Halo, {{ Auth::user()->name }}</span>
        </div>
    </div>
    
    <!-- Form Card -->
    <div class="card form-card">
        <div class="card-body p-4">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name') }}" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                id="email" name="email" value="{{ old('email') }}" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="division" class="form-label">Divisi</label>
                            <select class="form-select @error('division') is-invalid @enderror" 
                                id="division" name="division" required>
                                <option value="" disabled selected>Pilih Divisi</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division }}" {{ old('division') == $division ? 'selected' : '' }}>
                                        {{ $division }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select @error('role') is-invalid @enderror" 
                                id="role" name="role" required>
                                <option value="" disabled selected>Pilih Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                        {{ $role }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                id="password" name="password" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" 
                                id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('dashboard.users') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Any additional JavaScript needed for the create user page
</script>
@endsection