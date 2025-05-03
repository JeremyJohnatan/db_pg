@extends('layouts.app')

@section('title', 'Pengaturan Akun | PG Rajawali I')

@section('styles')
<style>
    :root {
        --primary: #004a94;
        --secondary: #f5f7fb;
    }
    
    /* Card Styling */
    /*.settings-card {
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border: none;
    }
    
    .settings-card .card-header {
        background-color: var(--primary);
        color: white;
        font-weight: 600;
        padding: 15px 20px;
    }
    
    /* Form Elements */
    .form-control {
        border-radius: 8px;
        padding: 10px 15px;
        border: 1px solid #e0e0e0;
    }
    
    .form-label {
        font-weight: 500;
        color: #555;
    }
    
    /* Quick Access */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }
    
    .quick-action-card {
        background: white;
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        border: 1px solid #eee;
    }
    
    .quick-action-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .quick-action-card i {
        font-size: 1.5rem;
        color: var(--primary);
        margin-bottom: 10px;
    }
    
    /* Notes Section */
    .notes-container {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 25px;
        border: 1px solid #eee;
    }
    
    .notes-content {
        min-height: 100px;
        border: 1px dashed #ddd;
        border-radius: 8px;
        padding: 15px;
        background-color: #fafafa;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .quick-actions {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endsection

@section('content')
<div class="container py-4">
        <!-- Quick Actions -->
        <h5 class="mb-3">Pintasan Cepat</h5>
        <div class="quick-actions">
            <a href="{{ route('profile') }}" class="quick-action-card text-decoration-none text-dark">
                <i class="fas fa-user-edit"></i>
                <div>Edit Profil</div>
            </a>
            <a href="#" class="quick-action-card text-decoration-none text-dark">
            <i class="fas fa-lock"></i>
            <div>Keamanan</div>
            </a>
            <a href="#" class="quick-action-card text-decoration-none text-dark">
                <i class="fas fa-bell"></i>
                <div>Notifikasi</div>
            </a>
            <a href="#" class="quick-action-card text-decoration-none text-dark">
                <i class="fas fa-palette"></i>
                <div>Tema</div>
            </a>
        </div>

        
    <!-- Main Settings Card -->
    <!--<div class="card settings-card mb-4">
        <div class="card-header">
            <h4 class="mb-0"><i class="fas fa-cog me-2"></i>Pengaturan Akun</h4>
        </div>
        
        <div class="card-body">
            <form method="POST" action="{{ route('pengaturan.update') }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="name" 
                               value="{{ Auth::user()->name }}" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" 
                               value="{{ Auth::user()->email }}" disabled>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" class="form-control" name="password"
                               placeholder="Kosongkan jika tidak ingin mengubah">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" name="password_confirmation">
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>-->

    <!-- Personal Notes -->
    <div class="notes-container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Catatan Pribadi</h5>
            <button class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i> Tambah Catatan
            </button>
        </div>
        <div class="notes-content">
            <p class="text-muted mb-0">Klik untuk menambahkan catatan pribadi...</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Password validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const password = document.querySelector('input[name="password"]');
        const confirmPass = document.querySelector('input[name="password_confirmation"]');
        
        if(password.value !== confirmPass.value) {
            e.preventDefault();
            alert('Konfirmasi password tidak sesuai!');
            password.focus();
        }
    });

    // Notes functionality
    document.querySelector('.notes-content').addEventListener('click', function() {
        const noteText = prompt('Masukkan catatan pribadi:');
        if(noteText) {
            this.innerHTML = `<p>${noteText}</p>`;
        }
    });
    
    // Quick action cards
    document.querySelectorAll('.quick-action-card').forEach(card => {
        card.addEventListener('click', function() {
            const action = this.querySelector('div').textContent;
            alert(`Aksi: ${action} akan dilakukan`);
        });
    });
</script>
@endsection