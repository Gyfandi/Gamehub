<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;
use App\Models\Discount;

class DiscountSeeder extends Seeder
{
    public function run(): void
    {
        $discounts = [
            ['title' => 'Cyberpunk 2077',           'percentage' => 50, 'days' => 14],
            ['title' => 'The Witcher 3: Wild Hunt',  'percentage' => 70, 'days' => 7],
            ['title' => 'Assassin\'s Creed Odyssey', 'percentage' => 60, 'days' => 10],
            ['title' => 'Dark Souls III',             'percentage' => 40, 'days' => 5],
            ['title' => 'Stardew Valley',             'percentage' => 33, 'days' => 3],
            ['title' => 'Hogwarts Legacy',            'percentage' => 25, 'days' => 7],
            ['title' => 'Hades',                      'percentage' => 20, 'days' => 14],
            ['title' => 'Red Dead Redemption 2',      'percentage' => 30, 'days' => 7],
            ['title' => 'God of War',                 'percentage' => 35, 'days' => 10],
            ['title' => 'Palworld',                   'percentage' => 15, 'days' => 3],
        ];

        foreach ($discounts as $d) {
            $game = Game::where('title', $d['title'])->first();
            if (!$game) continue;

            Discount::firstOrCreate(
                ['game_id' => $game->id],
                [
                    'percentage' => $d['percentage'],
                    'start_date' => now()->subDay(),
                    'end_date'   => now()->addDays($d['days']),
                ]
            );
        }
    }
}
