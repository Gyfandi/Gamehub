<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ambil semua game yang punya cover bukan default, tapi belum punya entry di game_images
        $games = DB::table('games')->get();

        foreach ($games as $game) {
            $hasImages = DB::table('game_images')->where('game_id', $game->id)->exists();

            if (!$hasImages && $game->image && $game->image !== '/images/games/default.jpg') {
                // Geser semua sort_order yang sudah ada (kalau ada) +1, lalu sisipkan cover di posisi 0
                DB::table('game_images')->where('game_id', $game->id)->increment('sort_order');

                DB::table('game_images')->insert([
                    'game_id' => $game->id,
                    'path' => $game->image,
                    'sort_order' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        // Tidak ada rollback otomatis untuk migrasi data ini (data sudah tergabung)
    }
};