@extends('layouts.app')

@section('title', 'Users | PG Rajawali I')

@section('styles')
<style>
    /* You can add any specific styles for the users page here */
    .table {
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    .table th {
        background-color: #f5f7fb;
        color: #6c757d;
    }
</style>
@endsection

@section('content')
<!-- Sidebar (same as dashboard layout) -->
<div class="sidebar d-flex flex-column">
    <!-- ... (copy the sidebar from your dashboard layout) ... -->
</div>

<!-- Main Content -->
<div class="main-content">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light mb-4">
        <!-- ... (copy the navbar from your dashboard layout) ... -->
    </nav>
    
    <!-- Users Content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Daftar Pengguna</h5>
                        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-2"></i>Tambah Pengguna
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Tanggal Dibuat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Any additional JavaScript for the users page
    document.addEventListener('DOMContentLoaded', function() {
        // Update active menu item
        const navLinks = document.querySelectorAll('.sidebar .nav-link');
        navLinks.forEach(link => {
            if (link.getAttribute('href') === "{{ route('dashboard.users') }}") {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    });
</script>
@endsection