<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat peran 'operator' jika belum ada [cite: 20]
        $operatorRole = Role::firstOrCreate(['name' => 'operator']);

        // Buat user admin
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@kumapan.test'], // Email unik sebagai acuan
            [
                'name' => 'Admin Operator',
                'password' => Hash::make('password123'), // Ganti dengan password yang aman
                'email_verified_at' => now(), // Langsung verifikasi emailnya
            ]
        );

        // Tugaskan peran 'operator' ke user admin
        $adminUser->assignRole($operatorRole);
    }
}