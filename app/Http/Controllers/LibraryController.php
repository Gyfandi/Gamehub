<?php

namespace App\Http\Controllers;

use App\Models\Library;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    public function index()
    {
        $ownedGames = Library::where('user_id', Auth::id())
            ->with('game.category')
            ->get()
            ->map(function ($lib) {
                // Dynamically simulate mock properties
                $game = $lib->game;
                if ($game) {
                    $game->purchase_date = $lib->purchase_date->format('Y-m-d');
                    
                    // Deterministic mock playtime based on id
                    $playtimeHours = ($game->id * 7) % 150 + 2;
                    $game->playtime = $playtimeHours . ' hours';
                    
                    // Deterministic mock last played
                    $daysAgo = ($game->id * 3) % 10 + 1;
                    $game->last_played = $daysAgo === 1 ? 'Yesterday' : $daysAgo . ' days ago';
                }
                return $game;
            })
            ->filter()
            ->toArray();

        return view('library', compact('ownedGames'));
    }
}
