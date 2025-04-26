@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('styles')
<style>
    .pengaturan-container {
        background-color: #fff;
        padding: 2rem;
        border-radius: 1rem;
        max-width: 600px;
        margin: 2rem auto;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
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
</style>
@endsection

@section('page-header')
    <h1>Pengaturan</h1>
@endsection

@section('content')
<div class="pengaturan-container">
    <h2>Informasi Akun</h2>
    <form action="{{ route('pengaturan.update') }}" method="POST">
        @csrf
        @method('POST')

        <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" required>
        </div>

        <div class="form-group">
            <label for="email">Alamat Email</label>
            <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" required>
        </div>

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="{{ auth()->user()->username ?? '' }}" required>
        </div>

        <div class="form-group" style="text-align: center;">
            <button type="submit">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
