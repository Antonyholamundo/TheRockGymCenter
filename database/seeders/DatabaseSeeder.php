<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuario administrador por defecto
        User::firstOrCreate(
            ['email' => 'admin@therockgym.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('admin123'),
            ]
        );
    }
}
