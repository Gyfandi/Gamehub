<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@gamehub.com'],
            [
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'demo@gamehub.com'],
            [
                'username' => 'demo_user',
                'password' => Hash::make('demo123'),
                'role' => 'buyer',
            ]
        );

        User::firstOrCreate(
            ['email' => 'gamer@gamehub.com'],
            [
                'username' => 'pro_gamer',
                'password' => Hash::make('gamer123'),
                'role' => 'buyer',
            ]
        );
    }
}
