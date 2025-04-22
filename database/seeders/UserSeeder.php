<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;  // Import model User
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User Admin
        User::create([
            'name' => 'Admin Gula',
            'username' => 'admin',
            'password' => Hash::make('123'),
        ]);

        // User Tambahan - Operator
        User::create([
            'name' => 'Operator Pabrik',
            'username' => 'operator',
            'password' => Hash::make('123'),
        ]);

        // User Tambahan - Supervisor
        User::create([
            'name' => 'Supervisor Gula',
            'username' => 'supervisor',
            'password' => Hash::make('123'),
        ]);
    }
}
