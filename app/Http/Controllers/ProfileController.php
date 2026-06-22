<?php

namespace App\Http\Controllers;

use App\Models\Library;
use App\Models\Wishlist;
use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $libraryCount = Library::where('user_id', $user->id)->count();
        $wishlistCount = Wishlist::where('user_id', $user->id)->count();
        $reviewCount = Review::where('user_id', $user->id)->count();

        $recentTransactions = Transaction::where('user_id', $user->id)
            ->with('details.game')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('profile', compact('user', 'libraryCount', 'wishlistCount', 'reviewCount', 'recentTransactions'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username' => 'required|string|min:3|max:50|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:100|unique:users,email,' . $user->id,
        ]);

        $user->username = $request->username;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:4']);
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
