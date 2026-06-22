<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlisted = Wishlist::where('user_id', Auth::id())
            ->with('game.category', 'game.publisher', 'game.activeDiscount')
            ->orderByDesc('created_at')
            ->get();

        return view('wishlist', compact('wishlisted'));
    }

    public function toggle(Request $request)
    {
        $request->validate(['game_id' => 'required|exists:games,id']);
        $userId = Auth::id();
        $gameId = intval($request->game_id);

        $existing = Wishlist::where('user_id', $userId)->where('game_id', $gameId)->first();

        if ($existing) {
            $existing->delete();
            $status = 'removed';
        } else {
            Wishlist::create(['user_id' => $userId, 'game_id' => $gameId]);
            $status = 'added';
        }

        if ($request->ajax()) {
            return response()->json(['status' => $status]);
        }

        return back()->with('success', $status === 'added' ? 'Added to wishlist.' : 'Removed from wishlist.');
    }
}
