<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Menampilkan daftar pengguna
     */
    public function index()
    {
        $users = User::all();
        return view('dashboard.users', compact('users'));
    }

    /**
     * Menampilkan form tambah pengguna baru
     */
    public function create()
    {
        return view('dashboard.users-create');
    }

    /**
     * Menyimpan pengguna baru
     */
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'nullable|in:admin,user,manager', // Sesuaikan dengan role yang ada
        ]);

        // Buat pengguna baru
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role'] ?? 'user', // Default role
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('dashboard.users')
            ->with('success', 'Pengguna berhasil ditambahkan');
    }

    /**
     * Menampilkan form edit pengguna
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('dashboard.users-edit', compact('user'));
    }

    /**
     * Memperbarui data pengguna
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required', 
                'email', 
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => 'nullable|min:8|confirmed',
            'role' => 'nullable|in:admin,user,manager', // Sesuaikan dengan role yang ada
        ]);

        // Update data pengguna
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        
        // Update password hanya jika diisi
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        // Update role
        if (!empty($validatedData['role'])) {
            $user->role = $validatedData['role'];
        }

        $user->save();

        // Redirect dengan pesan sukses
        return redirect()->route('dashboard.users')
            ->with('success', 'Data pengguna berhasil diperbarui');
    }

    /**
     * Menghapus pengguna
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Cegah penghapusan user yang sedang login
        if ($user->id === auth()->id()) {
            return redirect()->route('dashboard.users')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri');
        }

        $user->delete();

        return redirect()->route('dashboard.users')
            ->with('success', 'Pengguna berhasil dihapus');
    }

    /**
     * Metode untuk mengubah status aktif pengguna
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        
        // Toggle status aktif
        $user->is_active = !$user->is_active;
        $user->save();

        return redirect()->route('dashboard.users')
            ->with('success', 'Status pengguna berhasil diperbarui');
    }
}