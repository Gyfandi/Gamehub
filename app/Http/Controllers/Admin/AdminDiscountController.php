<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Game;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::with('game')->orderByDesc('created_at')->get();
        $games = Game::orderBy('title')->get();
        return view('admin.discounts', compact('discounts', 'games'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'percentage' => 'required|integer|min:1|max:99',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $discount = Discount::create($request->only('game_id', 'percentage', 'start_date', 'end_date'));
        $game = Game::find($request->game_id);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Create Discount',
            'description' => "Created {$request->percentage}% discount for {$game->title}",
        ]);

        return back()->with('success', 'Discount created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'percentage' => 'required|integer|min:1|max:99',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $discount = Discount::findOrFail($id);
        $discount->update($request->only('percentage', 'start_date', 'end_date'));

        return back()->with('success', 'Discount updated successfully.');
    }

    public function destroy($id)
    {
        Discount::findOrFail($id)->delete();
        return back()->with('success', 'Discount deleted.');
    }
}
