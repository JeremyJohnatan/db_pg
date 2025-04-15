<!-- resources/views/dashboard/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PG Rajawali I')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* CSS yang sama seperti yang Anda berikan */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f0f4f8;
        }
        /* sisanya dari CSS... */
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo-container">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
            <h2>PG Rajawali I</h2>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Home
                </a>
            </li>
            <li>
                <a href="{{ route('analisis.produk') }}" class="{{ request()->routeIs('analisis.produk') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i> Analisis Produk
                </a>
            </li>
            <li>
                <a href="{{ route('analisis.pabrik') }}" class="{{ request()->routeIs('analisis.pabrik') ? 'active' : '' }}">
                    <i class="fas fa-industry"></i> Analisis Pabrik
                </a>
            </li>
            <li>
                <a href="{{ route('laporan') }}" class="{{ request()->routeIs('laporan') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i> Laporan
                </a>
            </li>
        </ul>
        <a href="{{ route('logout') }}" class="logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> Log Out
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    <!-- Main Content -->
    <div class="main">
        <div class="topbar">
            <div>
                @yield('page-header')
            </div>
            <div class="user-info">
                <i class="fas fa-user-circle"></i>
                <span>Halo, Admin</span>
            </div>
        </div>

        @yield('content')
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    @yield('scripts')
</body>
</html>