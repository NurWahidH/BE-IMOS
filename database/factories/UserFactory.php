<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            
            // Catatan: Jika factory dipanggil, kolom role_id akan butuh nilai.
            // Kamu bisa membiarkannya kosong jika tabel mengizinkan null, 
            // atau mengatur default-nya di dalam Seeder nanti.
        ];
    }
}