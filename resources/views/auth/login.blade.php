<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | PG Rajawali I</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('{{ asset('assets/images/background.jpg') }}');
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .login-box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 350px;
            z-index: 1;
        }
        .login-box h4 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .btn-login {
            background-color: #004a94;
            color: white;
            border: none;
            border-radius: 8px;
        }
        .btn-login:hover {
            background-color: #00376d;
        }
        .brand {
            position: absolute;
            top: 40px;
            left: 40px;
            color: white;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }
        .brand img {
            max-width: 200px;
            margin-bottom: 10px;
        }
        /* Kode CSS tambahan untuk toggle password */
        .input-group-text {
            cursor: pointer;
            padding: 0.375rem 0.75rem;
        }
        .eye-icon {
            width: 20px;
            height: 20px;
            fill: #666;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <!-- Brand Logo -->
    <div class="brand">
        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo PG">
        
    </div>

    <!-- Login Box -->
    <div class="login-box">
        <h4>Selamat Datang</h4>
        <!-- Display Error Message -->
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <!-- Login Form -->
        <form action="{{ route('login.action') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Masukkan Email..." required>
            </div>
            <div class="mb-3">
                <label for="password">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan Password..." required>
                    <span class="input-group-text" onclick="togglePassword()">
                        <svg class="eye-icon" id="eyeOpen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                        </svg>
                        <svg class="eye-icon hidden" id="eyeClosed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/>
                        </svg>
                    </span>
                </div>
            </div>
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Ingat Saya</label>
                </div>
            </div>
            <button type="submit" class="btn btn-login w-100">Masuk</button>
        </form>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const password = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');
            
            if (password.type === 'password') {
                password.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                password.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
</body>
</html>