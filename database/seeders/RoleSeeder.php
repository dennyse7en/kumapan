<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'pengguna']);
        Role::firstOrCreate(['name' => 'operator']);
        Role::firstOrCreate(['name' => 'approver']);
        Role::firstOrCreate(['name' => 'verifikator']);
    }
}
