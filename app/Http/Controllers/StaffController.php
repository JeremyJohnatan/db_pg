<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    public function dashboard()
    {
        // Fetch staff user information
        $user = DB::table('pkl.users')
            ->where('id', session('user_id'))
            ->first();

        // You can add more data retrieval as needed
        $dashboardData = [
            'user' => $user,
            // Add more dashboard-specific data here
        ];

        return view('dashboard.staff.dashboard', $dashboardData);
    }
}