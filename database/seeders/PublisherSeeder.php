<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PublisherSeeder extends Seeder
{
    public function run(): void
    {
        $publishers = [
            ['name' => 'FromSoftware', 'description' => 'Japanese game developer known for Souls series and Elden Ring.'],
            ['name' => 'CD Projekt Red', 'description' => 'Polish game developer behind The Witcher series and Cyberpunk 2077.'],
            ['name' => 'EA Sports', 'description' => 'Electronic Arts sports gaming division.'],
            ['name' => 'Valve', 'description' => 'Developer and publisher of Steam platform, Counter-Strike, and more.'],
            ['name' => 'Rockstar Games', 'description' => 'Publisher of Grand Theft Auto and Red Dead Redemption series.'],
            ['name' => 'Riot Games', 'description' => 'Developer of League of Legends, VALORANT, and more.'],
            ['name' => 'PUBG Corporation', 'description' => 'Developer of PLAYERUNKNOWN\'S BATTLEGROUNDS.'],
            ['name' => 'TiMi Studio', 'description' => 'Developer of Delta Force: Hawk Ops and mobile games.'],
            ['name' => 'Playground Games', 'description' => 'Microsoft developer behind Forza Horizon series.'],
            ['name' => 'Valve / IceFrog', 'description' => 'Developers of DOTA 2.'],
            ['name' => 'Codemasters / EA', 'description' => 'Developer of F1 racing simulation games.'],
            ['name' => 'Supergiant Games', 'description' => 'Indie developer of Hades and Pyre.'],
            ['name' => 'Activision', 'description' => 'Publisher of Call of Duty and other major franchises.'],
            ['name' => 'Ubisoft', 'description' => 'French developer of Assassin\'s Creed, Far Cry, and more.'],
            ['name' => 'Bandai Namco', 'description' => 'Japanese publisher of Tekken, Dark Souls, and Naruto games.'],
            ['name' => 'Sony Interactive', 'description' => 'Publisher of PlayStation exclusives now on PC.'],
            ['name' => 'Nintendo / Yuzu', 'description' => 'Developer of iconic IPs like Zelda and Mario.'],
            ['name' => '2K Games', 'description' => 'Publisher of Borderlands, BioShock, and NBA 2K series.'],
            ['name' => 'Blizzard Entertainment', 'description' => 'Developer of Overwatch, World of Warcraft, Diablo.'],
            ['name' => 'Square Enix', 'description' => 'Publisher of Final Fantasy, Kingdom Hearts, and more.'],
        ];

        foreach ($publishers as $pub) {
            DB::table('publishers')->updateOrInsert(
                ['name' => $pub['name']],
                [
                    'description' => $pub['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}