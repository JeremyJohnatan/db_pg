@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Settings') }}</div>

                <div class="card-body">
                    <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab" aria-controls="password" aria-selected="true">
                                <i class="fa fa-key"></i> Ubah Password
                            </button>
                        </li>
                        <!-- Tab lainnya dapat ditambahkan di sini -->
                    </ul>
                    
                    <div class="tab-content mt-4" id="settingsTabContent">
                        <!-- Tab Ubah Password -->
                        <div class="tab-pane fade show active" id="password" role="tabpanel" aria-labelledby="password-tab">
                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form id="passwordForm" method="POST" action="{{ route('settings.password.update') }}">
                                @csrf

                                <div class="form-group row mb-3">
                                    <label for="current_password" class="col-md-4 col-form-label text-md-right">{{ __('Password Saat Ini') }}</label>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required autocomplete="current-password">
                                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('current_password')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password Baru') }}</label>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                        <div class="progress mt-2" style="height: 5px;">
                                            <div id="password-strength" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <small id="passwordHelpBlock" class="form-text text-muted">
                                            Password minimal 8 karakter, kombinasi huruf besar, huruf kecil, angka dan simbol.
                                        </small>
                                        @error('password')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">{{ __('Konfirmasi Password Baru') }}</label>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_confirmation">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                        <div id="password-match" class="form-text mt-1"></div>
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary" id="submit-btn">
                                            <i class="fa fa-save"></i> {{ __('Ubah Password') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Tab konten lainnya dapat ditambahkan di sini -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS Styles -->
<style>
/* Custom styles for settings page */
.card {
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    font-weight: bold;
    font-size: 1.1rem;
}

.nav-tabs .nav-link {
    color: #6c757d;
    border: none;
    border-bottom: 2px solid transparent;
    transition: all 0.2s ease;
}

.nav-tabs .nav-link:hover {
    color: #495057;
    border-color: #dee2e6;
}

.nav-tabs .nav-link.active {
    color: #0d6efd;
    background-color: transparent;
    border-bottom: 2px solid #0d6efd;
}

.toggle-password {
    cursor: pointer;
}

.progress-bar {
    transition: width 0.3s ease;
}

.is-valid {
    border-color: #28a745;
}

.is-invalid {
    border-color: #dc3545;
}

/* Media query for responsive design */
@media (max-width: 767px) {
    .col-form-label.text-md-right {
        text-align: left !important;
    }
    
    .offset-md-4 {
        margin-left: 0;
    }
}

/* Animation for success message */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.alert-success {
    animation: fadeIn 0.5s ease;
}
</style>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const toggleButtons = document.querySelectorAll('.toggle-password');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
    
    // Password strength checker
    const passwordInput = document.getElementById('password');
    const strengthBar = document.getElementById('password-strength');
    
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        
        // Check length
        if (password.length >= 8) strength += 20;
        
        // Check lowercase letters
        if (password.match(/[a-z]+/)) strength += 20;
        
        // Check uppercase letters
        if (password.match(/[A-Z]+/)) strength += 20;
        
        // Check numbers
        if (password.match(/[0-9]+/)) strength += 20;
        
        // Check special characters
        if (password.match(/[^a-zA-Z0-9]+/)) strength += 20;
        
        strengthBar.style.width = strength + '%';
        
        // Change color based on strength
        if (strength < 40) {
            strengthBar.className = 'progress-bar bg-danger';
        } else if (strength < 70) {
            strengthBar.className = 'progress-bar bg-warning';
        } else {
            strengthBar.className = 'progress-bar bg-success';
        }
    });
    
    // Password match checker
    const confirmInput = document.getElementById('password_confirmation');
    const matchStatus = document.getElementById('password-match');
    
    function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirmPassword = confirmInput.value;
        
        if (confirmPassword.length === 0) {
            matchStatus.textContent = '';
            matchStatus.className = 'form-text mt-1';
            return;
        }
        
        if (password === confirmPassword) {
            matchStatus.textContent = 'Password cocok';
            matchStatus.className = 'form-text text-success mt-1';
            confirmInput.classList.add('is-valid');
            confirmInput.classList.remove('is-invalid');
        } else {
            matchStatus.textContent = 'Password tidak cocok';
            matchStatus.className = 'form-text text-danger mt-1';
            confirmInput.classList.add('is-invalid');
            confirmInput.classList.remove('is-valid');
        }
    }
    
    passwordInput.addEventListener('input', checkPasswordMatch);
    confirmInput.addEventListener('input', checkPasswordMatch);
    
    // Form validation
    const form = document.getElementById('passwordForm');
    const submitBtn = document.getElementById('submit-btn');
    
    form.addEventListener('submit', function(event) {
        // Add loading state to button when form is submitted
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
    });
    
    // Close alert automatically after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const closeButton = new bootstrap.Alert(alert);
            closeButton.close();
        }, 5000);
    });
});
</script>
@endsection