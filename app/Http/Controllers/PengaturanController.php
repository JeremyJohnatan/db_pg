<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengaturanController extends Controller
{
    public function index()
    {

          $user = Auth::user();

    if ($user->role === 'Admin') {
        return view('dashboard.pengaturanadmin', compact('user'));
    } elseif ($user->role === 'Staff') {
        return view('dashboard.pengaturan', compact('user'));
    } else {
        abort(403, 'Role tidak dikenal');
    }

        return view('dashboard.pengaturan');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'username' => 'required|string|max:255',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->save();

        return redirect()->route('pengaturan')->with('success', 'Pengaturan akun berhasil diperbarui.');
    }
}
