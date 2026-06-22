<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Game;
use App\Models\Library;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function checkoutView()
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        if (!$cart) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $cartItems = CartItem::where('cart_id', $cart->id)
            ->with('game.activeDiscount')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $totalPrice = $cartItems->sum(function ($item) {
            return $item->game->final_price;
        });

        return view('checkout', compact('cartItems', 'totalPrice'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
        ]);

        $userId = Auth::id();
        $cart = Cart::where('user_id', $userId)->first();

        if (!$cart) {
            return redirect()->route('cart')->with('error', 'Cart not found.');
        }

        $cartItems = CartItem::where('cart_id', $cart->id)
            ->with('game.activeDiscount')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $totalPrice = $cartItems->sum(function ($item) {
            return $item->game->final_price;
        });

        // Perform transactional database commits
        DB::transaction(function () use ($userId, $cart, $cartItems, $totalPrice, $request) {
            // Create Transaction record
            $transaction = Transaction::create([
                'user_id' => $userId,
                'total' => $totalPrice,
                'payment_method' => $request->payment_method
            ]);

            foreach ($cartItems as $item) {
                $game = $item->game;
                
                // Decrement stock if stock is tracked
                if ($game->stock > 0) {
                    $game->decrement('stock');
                }

                // Add Transaction Detail
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'game_id' => $game->id,
                    'price' => $game->final_price
                ]);

                // Add to User's Library
                Library::firstOrCreate([
                    'user_id' => $userId,
                    'game_id' => $game->id,
                    'purchase_date' => now()
                ]);
            }

            // Clear Cart items
            CartItem::where('cart_id', $cart->id)->delete();
        });

        return redirect()->route('library')->with('success', 'Purchase completed successfully! Game(s) added to your library.');
    }
}
