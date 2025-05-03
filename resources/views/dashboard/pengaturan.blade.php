@extends('layouts.app')

@section('title', 'Pengaturan Akun | PG Rajawali I')

@section('styles')
<style>
    :root {
        --primary: #004a94;  /* Biru */
        --secondary: #f5f7fb;
        --button-bg: #004a94; /* Warna biru untuk tombol */
        --button-text: #ffffff; /* Teks tombol putih */
    }

    body {
        background-color: var(--secondary);
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

    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #a0a0a0;
    }

    .search-bar input {
        padding-left: 40px;
        border-radius: 20px;
        border: 1px solid #e0e0e0;
        background-color: #f5f7fb;
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
        background: #f8f9fc;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .shortcut-btn {
        background-color: var(--button-bg); /* Tombol biru */
        color: var(--button-text); /* Teks tombol putih */
        border-radius: 0 !important;
    }

    .shortcut-btn:hover {
        background-color: darken(var(--button-bg), 10%); /* Warna lebih gelap saat hover */
    }
</style>
@endsection

@section('content')
<nav class="navbar navbar-expand-lg navbar-light">
  <div class="container-fluid">
    <h5 class="mb-0">Pengaturan</h5>
    <div class="d-flex align-items-center">
      <div class="position-relative me-3">
        <i class="fas fa-search search-icon"></i>
        <input type="text" class="form-control ps-5 search-bar" placeholder="Search">
      </div>
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

<div class="container mt-4">
  {{-- Kotak Pintasan --}}
  <div class="content-box">
    <h5 class="mb-3">Pintasan</h5>
    <div class="d-flex flex-wrap gap-3">
            <a href="{{ route('profile') }}" class="btn btn-light border shadow-sm px-4 py-2 rounded-0 shortcut-btn">
        <i class="fas fa-user-edit me-2"></i>Edit Profil
        </a>


      </button>
      <button class="btn btn-light border shadow-sm px-4 py-2 rounded-0 shortcut-btn">
        <i class="fas fa-lock me-2"></i>Keamanan
      </button>
      <button class="btn btn-light border shadow-sm px-4 py-2 rounded-0 shortcut-btn">
        <i class="fas fa-bell me-2"></i>Notifikasi
      </button>
      <button class="btn btn-light border shadow-sm px-4 py-2 rounded-0 shortcut-btn">
        <i class="fas fa-palette me-2"></i>Tema
      </button>
    </div>
  </div>

  {{-- Kotak Catatan --}}
  <div class="content-box">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Catatan Pribadi</h5>
      <button class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i>Tambah Catatan
      </button>
    </div>
    <textarea class="form-control" rows="3"
      placeholder="Klik untuk menambahkan catatan pribadi..."></textarea>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
@endsection
