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
                    <span class="input-group-text">
                        <a href="#" onclick="togglePassword();return false;">
                            👁️
                        </a>
                    </span>
                </div>
            </div>
            <div class="d-flex justify-content-between mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Ingat Saya</label>
                </div>
                <a href="#">Lupa Password?</a>
            </div>
            <button type="submit" class="btn btn-login w-100">Masuk</button>
        </form>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const password = document.getElementById('password');
            password.type = password.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>
