<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Game;
use App\Models\Library;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cartItems = CartItem::where('cart_id', $cart->id)
            ->with('game.activeDiscount')
            ->get();

        $totalPrice = $cartItems->sum(function ($item) {
            return $item->game->final_price;
        });

        return view('cart', compact('cartItems', 'totalPrice'));
    }

    public function add(Request $request)
    {
        $request->validate(['game_id' => 'required|exists:games,id']);
        $gameId = intval($request->game_id);
        $userId = Auth::id();

        // Check if game is owned in user's library
        $owned = Library::where('user_id', $userId)->where('game_id', $gameId)->exists();
        if ($owned) {
            return back()->with('error', 'You already own this game.');
        }

        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        // Check if already in cart
        $exists = CartItem::where('cart_id', $cart->id)->where('game_id', $gameId)->exists();
        if ($exists) {
            return back()->with('error', 'Game is already in your cart.');
        }

        CartItem::create([
            'cart_id' => $cart->id,
            'game_id' => $gameId
        ]);

        if ($request->has('buy_now')) {
            return redirect()->route('cart');
        }

        return back()->with('success', 'Added to cart!');
    }

    public function remove($gameId)
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        if ($cart) {
            CartItem::where('cart_id', $cart->id)->where('game_id', $gameId)->delete();
            return redirect()->route('cart')->with('success', 'Removed game from cart.');
        }

        return redirect()->route('cart')->with('error', 'Cart not found.');
    }
}
