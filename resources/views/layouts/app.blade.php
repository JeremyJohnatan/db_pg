<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PG Rajawali I')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @yield('styles')
</head>
<body>
    <style>
       /* analisis pabrik */
       body {
        background-color: #f5f7fb;
        font-family: Arial, sans-serif;
    }

    .main-content {
        margin-left: 250px;
        padding-top: 80px; /* Sesuaikan dengan tinggi navbar */
        padding-left: 20px;
        padding-right: 20px;
        padding-bottom: 20px;
    }
    .navbar {
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        position: fixed; /* Membuat navbar tetap berada di posisinya */
        top: 0; /* Posisi di bagian atas */
        right: 0; /* Posisi di bagian kanan */
        left: 250px; /* Sesuaikan dengan lebar sidebar (250px) */
        z-index: 1030; /* Nilai z-index yang lebih tinggi untuk memastikan navbar di atas semua konten */
        width: calc(100% - 250px); /* Lebar navbar harus dikurangi lebar sidebar */
    }
    .navbar .search-bar {
        position: relative;
    }
    .navbar .search-bar input {
        padding-left: 40px; /* Space for the icon */
        border-radius: 20px;
        border: 1px solid #e0e0e0;
        background-color: #f5f7fb;
    }
    .navbar .search-bar .search-icon {
        position: absolute;
        left: 15px;
        top: 48%;
        transform: translateY(-50%);
        color: #a0a0a0;
        pointer-events: none; /* Ensures icon doesn't interfere with input */
        z-index: 10;
    }
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }
    .card-title {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 8px;
    }
    .card-value {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 0;
    }
    .chart-container {
        height: 250px;
        background-color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #a0a0a0;
        padding: 15px;
        border-radius: 8px;
    }
    .user-avatar {
        width: 32px;
        height: 32px;
        background-color: #004a94;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        cursor: pointer;
    }
    /* Tambahan style untuk dropdown profile */
    .profile-dropdown {
        position: relative;
    }
    .profile-dropdown .dropdown-menu {
        right: 0;
        left: auto;
    }

    /* Style khusus untuk analisis pabrik */
    .factory-card {
        background-color: white;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    .factory-header {
        display: flex;
        justify-content: space-between;
        padding: 15px 20px;
        border-bottom: 1px solid #e0e0e0;
        font-weight: bold;
        background-color: #f8f9fa;
        border-radius: 10px 10px 0 0;
    }
    .factory-content {
        padding: 5px 20px;
    }
    .factory-row {
        display: flex;
        align-items: center;
        padding: 15px 0;
    }
    .factory-row:not(:last-child) {
        border-bottom: 1px solid #e0e0e0;
    }
    .factory-name {
        width: 80px;
        font-weight: 500;
    }
    .progress-container {
        flex-grow: 1;
        height: 25px;
        background-color: #e9ecef;
        margin: 0 20px;
        border-radius: 5px;
        overflow: hidden;
    }
    .progress-bar {
        height: 100%;
        background-color: #004a94;
        border-radius: 5px;
    }
    .factory-value {
        width: 100px;
        text-align: right;
        font-weight: 500;
    }

    /* analisis produk */
    body {
        background-color: #f5f7fb;
        font-family: Arial, sans-serif;
    }
        /.main-content {
        margin-left: 250px;
        padding-top: 80px; /* Sesuaikan dengan tinggi navbar */
        padding-left: 20px;
        padding-right: 20px;
        padding-bottom: 20px;
    }   
    .navbar {
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        position: fixed; /* Membuat navbar tetap berada di posisinya */
        top: 0; /* Posisi di bagian atas */
        right: 0; /* Posisi di bagian kanan */
        left: 250px; /* Sesuaikan dengan lebar sidebar (250px) */
        z-index: 1030; /* Nilai z-index yang lebih tinggi untuk memastikan navbar di atas semua konten */
        width: calc(100% - 250px); /* Lebar navbar harus dikurangi lebar sidebar */
        transition: box-shadow 0.3s ease; /* Transisi yang halus saat scroll */
    }
    .navbar.scrolled {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Efek bayangan saat di-scroll */
    }
    .navbar .search-bar {
        position: relative;
    }
    .navbar .search-bar input {
        padding-left: 40px; /* Space for the icon */
        border-radius: 20px;
        border: 1px solid #e0e0e0;
        background-color: #f5f7fb;
    }
    .navbar .search-bar .search-icon {
        position: absolute;
        left: 15px;
        top: 48%;
        transform: translateY(-50%);
        color: #a0a0a0;
        pointer-events: none; /* Ensures icon doesn't interfere with input */
        z-index: 10;
    }
    .user-avatar {
        width: 32px;
        height: 32px;
        background-color: #004a94;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        cursor: pointer;
    }
    /* Tambahan style untuk dropdown profile */
    .profile-dropdown {
        position: relative;
    }
    .profile-dropdown .dropdown-menu {
        right: 0;
        left: auto;
    }
    
    /* Analisis Produk specific styles */
    .page-title {
        font-size: 1.8rem;
        font-weight: bold;
        margin-bottom: 20px;
    }
    .content-box {
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    .product-table {
        width: 100%;
        border-collapse: collapse;
    }
    .product-table th {
        text-align: left;
        padding: 15px 20px;
        border-bottom: 1px solid #b0c4de;
        font-weight: bold;
        color: #333;
    }
    .product-table td {
        padding: 15px 20px;
        border-bottom: 1px solid #b0c4de;
    }
    .product-table tr:last-child td {
        border-bottom: none;
    }
    .bar-container {
        width: 100%;
        background-color: #8a8a8a;
        height: 30px;
        border-radius: 4px;
    }

    /* index */
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }
    .card-title {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 8px;
    }
    .card-value {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 0;
    }
    .chart-container {
        height: 250px;
        background-color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #a0a0a0;
        border-radius: 8px;
    }
    .user-avatar {
        width: 32px;
        height: 32px;
        background-color: #004a94;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        cursor: pointer;
    }
    /* Tambahan style untuk dropdown profile */
    .profile-dropdown {
        position: relative;
    }
    .profile-dropdown .dropdown-menu {
        right: 0;
        left: auto;
    }

    /* laporan */
    .content-area {
            padding: 20px;
        }

        .report-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 20px;
        }

        .report-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .report-card-title {
            color: #333;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .report-actions button.btn-primary {
            background-color: #004a94;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s;
        }

        .report-actions button.btn-primary:hover {
            background-color: #003366;
        }

        .report-table-container {
            overflow-x: auto;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .report-table th,
        .report-table td {
            border: 1px solid #e0e0e0;
            padding: 10px;
            text-align: left;
            font-size: 0.9rem;
        }

        .report-table th {
            background-color: #f5f7fb;
            font-weight: bold;
            color: #333;
        }

        .no-data {
            color: #777;
            font-style: italic;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background-color: #004a94;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            cursor: pointer;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .action-buttons .btn {
            padding: 5px 8px;
            font-size: 0.8rem;
        }

        /* Modal styles for preview */
        .modal-preview {
            max-width: 90%;
        }

        .modal-preview .modal-body {
            height: 70vh;
        }

        .modal-preview iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Tambahan style untuk dropdown profile */
        .profile-dropdown {
            position: relative;
        }

        .profile-dropdown .dropdown-menu {
            right: 0;
            left: auto;
        }

        /* pengaturan */
        .main-content {
        margin-left: 250px;
        padding: 80px 20px 20px 20px;
    }

    .pengaturan-container {
        background-color: #fff;
        padding: 2rem;
        border-radius: 1rem;
        max-width: 600px;
        margin: 2rem auto;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }

    .pengaturan-container h2 {
        text-align: center;
        margin-bottom: 1.5rem;
        font-size: 1.8rem;
        font-weight: bold;
    }

    .form-group {
        margin-bottom: 1.2rem;
    }

    .form-group label {
        font-weight: 600;
        display: block;
        margin-bottom: 0.5rem;
    }

    .form-group input {
        width: 100%;
        padding: 0.6rem;
        border: 1px solid #ccc;
        border-radius: 0.5rem;
    }

    .form-group button {
        background-color: #007bff;
        color: white;
        padding: 0.6rem 1.5rem;
        border: none;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .form-group button:hover {
        background-color: #0056b3;
    }

    /* profile */
    body {
        background-color: #f5f7fb;
        font-family: Arial, sans-serif;
    }
    .sidebar {
        background-color: #004a94;
        color: white;
        min-height: 100vh;
        position: fixed;
        width: 250px;
        z-index: 1020;
    }
    .sidebar .logo {
        padding: 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        background-color: #004a94;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .sidebar .logo a {
        display: block;
        width: 100%;
        text-align: center;
        cursor: pointer;
    }
    .sidebar .logo a:hover {
        opacity: 0.9;
    }
    .sidebar .logo img {
        max-width: 100%;
        padding: 10px;
    }
    .sidebar .nav-link {
        color: white;
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        transition: all 0.3s;
    }
    .sidebar .nav-link:hover, .sidebar .nav-link.active {
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
    }
    .sidebar .nav-link i {
        width: 20px;
        text-align: center;
    }
    .sidebar .nav-link .text-primary {
        color: #004a94 !important;
    }
    .main-content {
        margin-left: 250px;
        padding-top: 80px;
        padding-left: 20px;
        padding-right: 20px;
        padding-bottom: 20px;
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
    .navbar .search-bar {
        position: relative;
    }
    .navbar .search-bar input {
        padding-left: 40px;
        border-radius: 20px;
        border: 1px solid #e0e0e0;
        background-color: #f5f7fb;
    }
    .navbar .search-bar .search-icon {
        position: absolute;
        left: 15px;
        top: 48%;
        transform: translateY(-50%);
        color: #a0a0a0;
        pointer-events: none;
        z-index: 10;
    }
    .user-avatar {
        width: 32px;
        height: 32px;
        background-color: #004a94;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        cursor: pointer;
    }
    .profile-dropdown {
        position: relative;
    }
    .profile-dropdown .dropdown-menu {
        right: 0;
        left: auto;
    }
    
    /* Profile Page Specific Styles */
    .profile-header {
        background-color: white;
        border-radius: 10px;
        padding: 30px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    .profile-avatar {
        width: 100px;
        height: 100px;
        background-color: #004a94;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        font-weight: bold;
        margin-right: 25px;
    }
    .profile-info h2 {
        font-size: 24px;
        margin-bottom: 5px;
    }
    .profile-info p {
        color: #666;
        margin-bottom: 3px;
    }
    .profile-content {
        background-color: white;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    .nav-tabs {
        border-bottom: 1px solid #ddd;
        margin-bottom: 20px;
    }
    .nav-tabs .nav-link {
        color: #666;
        border: none;
        padding: 10px 20px;
        border-bottom: 3px solid transparent;
        transition: all 0.3s;
    }
    .nav-tabs .nav-link:hover {
        border-bottom: 3px solid #ccc;
    }
    .nav-tabs .nav-link.active {
        color: #004a94;
        border-bottom: 3px solid #004a94;
        font-weight: bold;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-label {
        font-weight: 500;
        margin-bottom: 8px;
        display: block;
    }
    .form-control {
        border-radius: 5px;
        border: 1px solid #ddd;
        padding: 10px 15px;
    }
    .btn-save {
        background-color: #004a94;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .btn-save:hover {
        background-color: #003366;
    }
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 24px;
    }
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .toggle-slider {
        background-color: #004a94;
    }
    input:checked + .toggle-slider:before {
        transform: translateX(26px);
    }
    .activity-item {
        padding: 15px 0;
        border-bottom: 1px solid #eee;
    }
    .activity-item:last-child {
        border-bottom: none;
    }
    .activity-icon {
        width: 40px;
        height: 40px;
        background-color: #e6f0fa;
        color: #004a94;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }
    .activity-content {
        flex: 1;
    }
    .activity-title {
        font-weight: 500;
        margin-bottom: 3px;
    }
    .activity-time {
        color: #999;
        font-size: 0.8rem;
    }
    
    /* Pop-up Alert Styles */
    .alert-popup {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        color: white;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        z-index: 9999;
        display: none;
        animation: slideIn 0.5s forwards;
    }
    
    .alert-success {
        background-color: #4CAF50;
    }
    
    .alert-warning {
        background-color: #ff9800;
    }
    
    .alert-error {
        background-color: #f44336;
    }
    
    @keyframes slideIn {
        0% {
            transform: translateX(100%);
            opacity: 0;
        }
        100% {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes fadeOut {
        0% {
            opacity: 1;
        }
        100% {
            opacity: 0;
        }
    }
    
    .alert-popup.show {
        display: block;
    }
    
    .alert-popup.hide {
        animation: fadeOut 0.5s forwards;
    }
    
    /* Removed filter dropdown styles */
    
    .activity-hidden {
        display: none !important;
    }
    
    /* Style untuk debug label */
    .debug-label {
        background-color: #fffde7;
        padding: 5px;
        margin: 5px 0;
        font-size: 12px;
        font-family: monospace;
        border-radius: 3px;
        border-left: 3px solid #ffc107;
    }

    /* user */
    body {
        background-color: #f5f7fb;
        font-family: Arial, sans-serif;
    }
    .navbar {
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        position: fixed; /* Membuat navbar tetap berada di posisinya */
        top: 0; /* Posisi di bagian atas */
        right: 0; /* Posisi di bagian kanan */
        left: 250px; /* Sesuaikan dengan lebar sidebar (250px) */
        z-index: 1000; /* Memastikan navbar berada di atas konten lain */
        padding: 10px 20px;
    }
    .navbar-content {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .navbar .search-bar {
        position: relative;
    }
    .navbar .search-bar input {
        padding-left: 40px; /* Space for the icon */
        border-radius: 20px;
        border: 1px solid #e0e0e0;
        background-color: #f5f7fb;
    }
    .navbar .search-bar .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #a0a0a0;
        pointer-events: none; /* Ensures icon doesn't interfere with input */
        z-index: 10;
    }
    .navbar .user-info {
        display: flex;
        align-items: center;
    }
    .navbar .user-avatar {
        width: 32px;
        height: 32px;
        background-color: #004a94;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        cursor: pointer;
    }
    /* Styling untuk Dropdown Profile */
    .profile-dropdown {
        position: relative;
    }
    .profile-dropdown .dropdown-menu {
        right: 0;
        left: auto;
    }
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }
    .card-title {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 8px;
    }
    .card-value {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 0;
    }
    .chart-container {
        height: 250px;
        background-color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #a0a0a0;
        border-radius: 8px;
    }
    /* Styling untuk tabel pengguna */
    .user-table {
        width: 100%;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
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
    .add-user-btn {
        background-color: #004a94;
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
        text-decoration: none;
        color: white;
    }
    .btn-primary {
        background-color: #004a94;
        border-color: #004a94;
    }
    .btn-primary:hover {
        background-color: #003d87;
        border-color: #003d87;
    }
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }
    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }
    .action-buttons form {
        display: inline;
    }


    body {
        background-color: #f5f7fb;
        font-family: Arial, sans-serif;
    }
    .sidebar {
        background-color: #004a94;
        color: white;
        min-height: 100vh;
        position: fixed;
        width: 250px;
    }
    .sidebar .logo {
        padding: 15px;
        font-weight: bold;
        font-size: 1.2rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    .sidebar .nav-link {
        color: white;
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        transition: all 0.3s;
    }
    .sidebar .nav-link:hover, .sidebar .nav-link.active {
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
    }
    .sidebar .nav-link i {
        width: 20px;
        text-align: center;
    }
    .sidebar .nav-link .text-primary {
        color: #004a94 !important;
    }
    .main-content {
        margin-left: 250px;
        padding: 80px 20px 20px 20px; /* Tambahkan padding-top yang lebih besar */
    }
    .navbar {
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        position: fixed; /* Membuat navbar tetap berada di posisinya */
        top: 0; /* Posisi di bagian atas */
        right: 0; /* Posisi di bagian kanan */
        left: 250px; /* Sesuaikan dengan lebar sidebar (250px) */
        z-index: 1000; /* Memastikan navbar berada di atas konten lain */
    }
    .navbar .search-bar {
        position: relative;
    }
    .navbar .search-bar input {
        padding-left: 40px; /* Space for the icon */
        border-radius: 20px;
        border: 1px solid #e0e0e0;
        background-color: #f5f7fb;
    }
    .navbar .search-bar .search-icon {
        position: absolute;
        left: 15px;
        top: 48%;
        transform: translateY(-50%);
        color: #a0a0a0;
        pointer-events: none; /* Ensures icon doesn't interfere with input */
        z-index: 10;
    }



      
        </style>
<div class="sidebar d-flex flex-column">
        <div class="logo d-flex align-items-center">
            <img src="{{ asset('assets/images/logo.png') }}" alt="PG Rajawali I" class="img-fluid"
                style="max-width: 100%; padding: 10px;">
        </div>
        <div class="nav flex-column mt-4">
            <a href="{{ route('dashboard') }}" class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}">
                <div class="d-flex align-items-center">
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 40px; height: 40px;">
                        <i class="fas fa-home text-primary"></i>
                    </div>
                    <span>Dashboard</span>
                </div>
            </a>
            <a href="{{ route('dashboard.analisis-produk') }}" class="nav-link">
                <div class="d-flex align-items-center">
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 40px; height: 40px;">
                        <i class="fas fa-box text-primary"></i>
                    </div>
                    <span>Analisis Produk</span>
                </div>
            </a>
            <a href="{{ route('dashboard.analisis-pabrik') }}" class="nav-link">
                <div class="d-flex align-items-center">
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 40px; height: 40px;">
                        <i class="fas fa-industry text-primary"></i>
                    </div>
                    <span>Analisis Pabrik</span>
                </div>
            </a>
            <a href="{{ route('dashboard.laporan') }}" class="nav-link">
                <div class="d-flex align-items-center">
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 40px; height: 40px;">
                        <i class="fas fa-file-alt text-primary"></i>
                    </div>
                    <span>Laporan</span>
                </div>
            </a>
            <a href="{{ route('dashboard.users') }}"
                class="nav-link {{ request()->routeIs('dashboard.users') ? 'active' : '' }}">
                <div class="d-flex align-items-center">
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 40px; height: 40px;">
                        <i class="fas fa-users text-primary"></i>
                    </div>
                    <span>Users</span>
                </div>
            </a>
        </div>
        <!-- Logout link removed from sidebar -->
    </div>

    <div class="main-content">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light mb-4">
            <div class="container-fluid">
                <div class="d-flex align-items-center">
                    <div class="input-group me-3">
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        <input type="date" class="form-control" id="tanggal-mulai" name="tanggal_mulai"
                            value="{{ $tanggalMulai ?? '' }}">
                    </div>
                    <div class="input-group me-3">
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        <input type="date" class="form-control" id="tanggal-akhir" name="tanggal_akhir"
                            value="{{ $tanggalAkhir ?? '' }}">
                    </div>
                    <button class="btn btn-primary btn-sm" id="filter-tanggal">Filter</button>
                </div>
                <div class="d-flex align-items-center">
                    <div class="search-bar me-3">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="form-control" placeholder="Cari Laporan">
                    </div>
                    <!-- Profile dropdown menu -->
                    <div class="dropdown profile-dropdown">
                        <div class="d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="me-2">Halo, {{ Auth::user()->name }}</span>
                            <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user me-2"></i>
                                    Profil</a></li>
                            <li><a class="dropdown-item" href="{{ route('pengaturan') }}"><i class="fas fa-cog me-2"></i> Pengaturan</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Log Out
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
    @yield('content')
    
    </div>
    @yield('scripts')
</body>
</html>