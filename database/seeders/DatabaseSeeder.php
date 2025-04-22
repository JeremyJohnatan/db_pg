<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Gula',
            'username' => 'admin',
            'password' => bcrypt('123'),
        ]);
    }
}
