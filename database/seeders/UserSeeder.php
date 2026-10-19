<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Admin
        User::create([
            'name' => 'Admin DLH',
            'email' => 'admin@angkut.id',
            'phone' => '081234567890',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Akun Petugas
        User::create([
            'name' => 'Petugas Lapangan 1',
            'email' => 'petugas@angkut.id',
            'phone' => '089876543210',
            'password' => Hash::make('password123'),
            'role' => 'officer',
            'is_active' => true,
        ]);
    }
}