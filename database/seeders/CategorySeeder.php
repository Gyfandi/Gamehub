<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Action', 'RPG', 'Sports', 'Strategy', 'Racing',
            'Shooter', 'MOBA', 'Battle Royale', 'Simulation', 'Adventure',
            'Fighting', 'Horror', 'Puzzle', 'Sandbox', 'Stealth',
        ];

        foreach ($categories as $name) {
            DB::table('categories')->insertOrIgnore([
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
