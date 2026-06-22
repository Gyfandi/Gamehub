<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Library;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'recommendation' => 'required|in:1,0',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        $userId = Auth::id();
        $gameId = intval($request->game_id);

        // Verify user owns the game
        $owned = Library::where('user_id', $userId)->where('game_id', $gameId)->exists();
        if (!$owned) {
            return back()->with('error', 'You must own this game to leave a review.');
        }

        // Upsert review (one per user per game)
        Review::updateOrCreate(
            ['user_id' => $userId, 'game_id' => $gameId],
            [
                'recommendation' => (bool) $request->recommendation,
                'rating' => intval($request->rating),
                'comment' => $request->comment,
            ]
        );

        return back()->with('success', 'Thank you for your review!');
    }
}
