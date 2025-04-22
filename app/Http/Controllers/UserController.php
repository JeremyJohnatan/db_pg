<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::orderBy('name')->get();
        
        return view('dashboard.users', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // List of divisions for the dropdown
        $divisions = [
            'IT',
            'HRD',
            'Produksi',
            'Keuangan',
            'Marketing',
            'QA',
        ];
        
        // List of roles for the dropdown
        $roles = [
            'Admin',
            'Manager',
            'Staff',
            'Operator'
        ];
        
        return view('dashboard.users.create', compact('divisions', 'roles'));
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'division' => 'required|string|max:100',
            'role' => 'required|string|max:100',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'division' => $validated['division'],
            'role' => $validated['role'],
        ]);

        return redirect()->route('dashboard.users')
            ->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        // List of divisions for the dropdown
        $divisions = [
            'IT',
            'HRD',
            'Produksi',
            'Keuangan',
            'Marketing',
            'QA',
        ];
        
        // List of roles for the dropdown
        $roles = [
            'Admin',
            'Manager',
            'Staff',
            'Operator'
        ];
        
        return view('dashboard.users.edit', compact('user', 'divisions', 'roles'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'division' => 'required|string|max:100',
            'role' => 'required|string|max:100',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->division = $validated['division'];
        $user->role = $validated['role'];
        
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();

        return redirect()->route('dashboard.users')
            ->with('success', 'User berhasil diperbarui!');
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('dashboard.users')
                ->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan!');
        }
        
        $user->delete();

        return redirect()->route('dashboard.users')
            ->with('success', 'User berhasil dihapus!');
    }
}