<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Roles
        $masterAdmin = \Illuminate\Support\Facades\DB::table('role')->insertGetId(['nama_role' => 'master_admin']);
        $admin = \Illuminate\Support\Facades\DB::table('role')->insertGetId(['nama_role' => 'admin']);
        $userRole = \Illuminate\Support\Facades\DB::table('role')->insertGetId(['nama_role' => 'user']);
        $public = \Illuminate\Support\Facades\DB::table('role')->insertGetId(['nama_role' => 'public']);

        // 2. Buat User dummy dan berikan role_id (contoh: master_admin)
        User::factory()->create([
            'username' => 'testuser',
            'email' => 'testuser@antammedika.com',
            'password' => \Illuminate\Support\Facades\Hash::make('Password123'),
            'role_id' => $masterAdmin,
        ]);
    }
}
