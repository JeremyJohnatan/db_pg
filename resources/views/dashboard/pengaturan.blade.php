@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('styles')
<style>
    .main-content {
        padding: 20px;
    }

    .navbar {
        background-color: #fff;
        padding: 15px 20px;
        border-bottom: 1px solid #e5e5e5;
    }

    .search-bar {
        position: relative;
        width: 250px;
    }

    .search-bar input {
        padding-left: 35px;
        border-radius: 20px;
        border: 1px solid #ced4da;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        top: 10px;
        color: #6c757d;
    }

    .profile-dropdown {
        position: relative;
        cursor: pointer;
    }

    .user-avatar {
        width: 32px;
        height: 32px;
        background-color: #0d6efd;
        color: white;
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
<div class="main-content">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light mb-4">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Profil Pengguna</h5>
            <div class="d-flex align-items-center">
                <div class="search-bar me-3">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="form-control" placeholder="Cari">
                </div>
                <div class="profile-dropdown">
                    <div class="d-flex align-items-center" id="profileToggle">
                        <span class="me-2">Halo, Admin</span>
                        <div class="user-avatar" id="avatarButton" role="button" aria-expanded="false">A</div>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end shadow" id="profileMenu">
                        <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user me-2"></i> Profil</a></li>
                        <li><a class="dropdown-item" href="{{ route('pengaturan') }}"><i class="fas fa-cog me-2"></i> Pengaturan</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i> Keluar
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

    <div class="main-content">
        <!--<div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="account-info-container">
                    <h2>Informasi Akun</h2>
                    <form action="{{ route('pengaturan.update') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" id="name" name="name" value="Admin" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Alamat Email</label>
                            <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" value="admin" required>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>-->

        <!-- Notifikasi -->   


    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const avatarButton = document.getElementById('avatarButton');
        const profileMenu = document.getElementById('profileMenu');

        avatarButton.addEventListener('click', function (e) {
            e.stopPropagation();
            profileMenu.classList.toggle('show');
        });

        document.addEventListener('click', function (e) {
            if (!profileMenu.contains(e.target) && !avatarButton.contains(e.target)) {
                profileMenu.classList.remove('show');
            }
        });
    });
</script>
@endsection
