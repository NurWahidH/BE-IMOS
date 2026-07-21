<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $masterAdminRole = Role::where('nama_role', 'masterAdmin')->first();
        $adminRole = Role::where('nama_role', 'admin')->first();
        $userRole = Role::where('nama_role', 'user')->first();

        User::firstOrCreate(
            ['email' => 'masteradmin@antammedika.com'],
            [
                'role_id' => $masterAdminRole?->id,
                'username' => 'masteradmin',
                'password' => Hash::make('Password123'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@antammedika.com'],
            [
                'role_id' => $adminRole?->id,
                'username' => 'admin',
                'password' => Hash::make('Password123'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@antammedika.com'],
            [
                'role_id' => $userRole?->id,
                'username' => 'user',
                'password' => Hash::make('Password123'),
            ]
        );
    }
}
