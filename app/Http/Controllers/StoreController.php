<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\TransactionDetail;
use App\Models\Review;
use App\Models\Library;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function landing()
    {
        // Hero / Featured
        $featured = Game::orderByDesc('rating')->first();

        // Trending Games (Rating >= 4.5, limit 4)
        $trending = Game::orderByDesc('rating')->take(4)->get();

        // Special Offers (Has active discount)
        $specialOffers = Game::has('activeDiscount')
            ->with('activeDiscount')
            ->take(4)
            ->get();
        
        // If special offers count is less than 4, fallback to include some games without discounts
        if ($specialOffers->count() < 4) {
            $discountIds = $specialOffers->pluck('id');
            $additionalGames = Game::whereNotIn('id', $discountIds)->take(4 - $specialOffers->count())->get();
            $specialOffers = $specialOffers->concat($additionalGames);
        }

        // Top Sellers
        $topSellerIds = TransactionDetail::select('game_id')
            ->selectRaw('COUNT(*) as sales_count')
            ->groupBy('game_id')
            ->orderByDesc('sales_count')
            ->take(5)
            ->pluck('game_id');
        
        $topSellers = Game::whereIn('id', $topSellerIds)->get();
        if ($topSellers->isEmpty()) {
            $topSellers = Game::orderByDesc('rating')->take(5)->get();
        }

        // New Releases
        $newReleases = Game::orderByDesc('release_date')->take(4)->get();

        // Top Rated Games
        $topRated = Game::orderByDesc('rating')->take(4)->get();

        return view('landing', compact('featured', 'trending', 'specialOffers', 'topSellers', 'newReleases', 'topRated'));
    }

    public function catalog(Request $request)
    {
        $query = Game::query()->with('publisher', 'category', 'activeDiscount');

        // Apply Search by title, publisher name, or category name
        if ($request->filled('search')) {
            $search = strtolower($request->query('search'));
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(developer) LIKE ?', ["%{$search}%"])
                  ->orWhereHas('publisher', function($pub) use ($search) {
                      $pub->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]);
                  })
                  ->orWhereHas('category', function($cat) use ($search) {
                      $cat->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]);
                  });
            });
        }

        // Apply Category Filter
        if ($request->filled('category')) {
            $catIds = array_map('intval', (array) $request->query('category'));
            $query->whereIn('category_id', $catIds);
        }

        // Apply Sorting
        $sort = $request->query('sort', 'default');
        if ($sort === 'price_asc') {
            $query->orderBy('price');
        } elseif ($sort === 'price_desc') {
            $query->orderByDesc('price');
        } elseif ($sort === 'rating_desc') {
            $query->orderByDesc('rating');
        } else {
            $query->orderByDesc('release_date');
        }

        $games = $query->get();
        $categories = Category::all();
        $activeCategories = $request->query('category', []);

        return view('catalog', compact('games', 'categories', 'activeCategories'));
    }

    public function detail($id)
    {
        $game = Game::with('publisher', 'category', 'reviews.user', 'activeDiscount')->findOrFail($id);

        // System Requirements (Mocked per game category)
        $requirements = [
            'os' => 'Windows 10/11 64-bit',
            'processor' => 'Intel Core i5-8400 or AMD Ryzen 5 2600',
            'memory' => '12 GB RAM',
            'graphics' => 'NVIDIA GeForce GTX 1060 6GB or AMD Radeon RX 580 8GB',
            'storage' => '60 GB available space',
        ];

        // Custom requirements based on specific games
        if ($game->title === 'Portal 2' || $game->title === 'Dota 2') {
            $requirements['memory'] = '4 GB RAM';
            $requirements['graphics'] = 'NVIDIA GeForce 8600/9600GT or ATI/AMD Radeon HD2600/3600';
            $requirements['storage'] = '15 GB available space';
        }

        // Reviews recommendation positive percentage
        $posPercentage = $game->positive_review_percentage;

        // Related Games: same category, limit 4
        $relatedGames = Game::where('category_id', $game->category_id)
            ->where('id', '!=', $game->id)
            ->take(4)
            ->get();

        // Check ownership
        $isOwned = false;
        $isWishlisted = false;

        if (Auth::check()) {
            $isOwned = Library::where('user_id', Auth::id())->where('game_id', $game->id)->exists();
            $isWishlisted = Wishlist::where('user_id', Auth::id())->where('game_id', $game->id)->exists();
        }

        return view('detail', compact('game', 'requirements', 'posPercentage', 'relatedGames', 'isOwned', 'isWishlisted'));
    }
}
