<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuario administrador por defecto
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@nursehub.com',
            'password' => Hash::make('password'),
            'role' => UserRole::ADMIN,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Crear coordinador general de ejemplo
        User::create([
            'name' => 'La Planchada',
            'email' => 'coordinador@nursehub.com',
            'password' => Hash::make('password'),
            'role' => UserRole::COORDINADOR,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Crear jefe de piso de ejemplo (Pediatría)
        User::create([
            'name' => 'Lula Enfermera',
            'email' => 'jefe.pediatria@nursehub.com',
            'password' => Hash::make('password'),
            'role' => UserRole::JEFE_PISO,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Crear jefe de capacitación de ejemplo
        User::create([
            'name' => 'Patch Addams',
            'email' => 'capacitacion@nursehub.com',
            'password' => Hash::make('password'),
            'role' => UserRole::JEFE_CAPACITACION,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Crear enfermero de ejemplo
        User::create([
            'name' => 'Buen Samaritano',
            'email' => 'enfermero@nursehub.com',
            'password' => Hash::make('password'),
            'role' => UserRole::ENFERMERO,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}
