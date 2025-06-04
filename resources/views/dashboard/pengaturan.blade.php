@extends('layouts.app')

@section('title', 'Pengaturan Akun | PG Rajawali I')

@section('styles')
<style>
    :root {
        --primary: #004a94;
        --secondary: #f5f7fb;
        --button-bg: #004a94;
        --button-text: #ffffff;
    }

    body.light {
        --bg-color: var(--secondary);
        --text-color: #000000;
        --box-bg: #f8f9fc;
        --box-border: #e0e0e0;
    }

    body.dark {
    --bg-color: #fffbe6; /* warna cream muda */
    --text-color: #000000; /* teks tetap hitam agar kontras */
    --box-bg: #fffdf2; /* latar belakang kontainer dalam mode gelap */
    --box-border: #e0d9b9; /* border yang lebih soft */
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
    }*/

    /* Fitur Tambahan */
    .settings-card {
        background: var(--box-bg);
        border: 1px solid var(--box-border);
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
    }

    .settings-card h6 {
        color: var(--primary);
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .quick-settings-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

        .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .form-select {
        background-color: var(--box-bg);
        color: var(--text-color);
        border: 1px solid var(--box-border);
    }
</style>
@endsection

@section('content')
<nav class="navbar navbar-expand-lg navbar-light">
  <div class="container-fluid">
    <h5 class="mb-0">Pengaturan</h5>
    <div class="d-flex align-items-center">
      <!--<div class="position-relative me-3">
        <i class="fas fa-search search-icon"></i>
        <input type="text" class="form-control ps-5 search-bar" placeholder="Search">
      </div>-->
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
      <a href="{{ route('profile') }}" class="btn btn-light border shadow-sm px-4 py-2 rounded-3 shortcut-btn">
        <i class="fas fa-user-edit me-2"></i>Edit Profil
      </a>

    
      <a href="{{ route('profile') }}" class="btn btn-light border shadow-sm px-4 py-2 rounded-3 shortcut-btn">
        <i class="fas fa-user-edit me-2"></i>keamanan
      </a>


      <!--<button class="btn btn-light border shadow-sm px-4 py-2 rounded-3 shortcut-btn">
        <i class="fas fa-lock me-2"></i>Keamanan
      </button>-->
      <!--<button class="btn btn-light border shadow-sm px-4 py-2 rounded-3 shortcut-btn">
        <i class="fas fa-bell me-2"></i>Notifikasi
      </button>-->
      <button id="theme-toggle" class="btn btn-light border shadow-sm px-4 py-2 rounded-3 shortcut-btn">
        <i class="fas fa-palette me-2"></i>Tema
      </button>
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
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

<script>
 document.addEventListener('DOMContentLoaded', function () {
    // Toggle Theme
    const themeToggle = document.getElementById('theme-toggle');
    const body = document.body;

    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
      body.classList.remove('light');
      body.classList.add('dark');
    } else {
      body.classList.add('light');
    }

    themeToggle.addEventListener('click', function () {
      if (body.classList.contains('light')) {
        body.classList.remove('light');
        body.classList.add('dark');
        localStorage.setItem('theme', 'dark');
      } else {
        body.classList.remove('dark');
        body.classList.add('light');
        localStorage.setItem('theme', 'light');
      }
    });
// Catatan Pribadi
    const personalNotes = document.getElementById('personal-notes');
    const addNoteBtn = document.getElementById('add-note');

    addNoteBtn.addEventListener('click', function() {
      const noteText = prompt('Masukkan catatan pribadi:');
      if (noteText) {
        personalNotes.value = noteText;
      }
    });

    // Simpan preferensi notifikasi
    const notifSound = document.getElementById('notifSound');
    notifSound.addEventListener('change', function() {
      localStorage.setItem('notifSound', this.checked);
    });

    // Load preferensi yang disimpan
    if (localStorage.getItem('notifSound') === 'false') {
      notifSound.checked = false;
    }
  });


</script>

@endsection