<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ProfileController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    // Gunakan constructor untuk menerapkan middleware
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        return view('dashboard.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Tambahkan logging untuk melihat request yang masuk
        Log::info('Profile update request:', $request->all());
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
            'address' => 'nullable|string',
        ]);
        
        $user->update($validatedData);
        
        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'user' => $user
        ]);
    }
}