<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@commune.bj',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
        ])->forceFill(['email_verified_at' => now()])->save();

        // Opérateur test
        User::create([
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'password' => Hash::make('password123'),
            'role' => 'operateur',
            'is_active' => true,
        ])->forceFill(['email_verified_at' => now()])->save();

        // Catégories
        $categories = [
            ['name' => 'Artisanat', 'description' => 'Artisans et métiers manuels'],
            ['name' => 'Commerce', 'description' => 'Commerçants et vendeurs'],
            ['name' => 'Agriculture', 'description' => 'Agriculteurs et éleveurs'],
            ['name' => 'Services', 'description' => 'Prestataires de services'],
            ['name' => 'Technologie', 'description' => 'IT et nouvelles technologies'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
