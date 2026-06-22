<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameImage;
use App\Models\Publisher;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminGameController extends Controller
{
    public function index()
    {
        $games = Game::with('publisher', 'category', 'images')->orderByDesc('created_at')->get();
        $publishers = Publisher::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('admin.games', compact('games', 'publishers', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'price' => 'required|integer|min:0',
            'release_date' => 'required|date',
            'developer' => 'required|string|max:100',
            'publisher_id' => 'required|exists:publishers,id',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only(
            'title', 'description', 'price', 'release_date', 'developer',
            'publisher_id', 'category_id', 'stock'
        );

        $files = $request->hasFile('images') ? $request->file('images') : [];
        $data['image'] = '/images/games/default.jpg';

        $game = Game::create($data);

        if (count($files) > 0) {
            $order = 0;
            foreach ($files as $file) {
                $path = $file->store('games/gallery', 'public');
                GameImage::create([
                    'game_id' => $game->id,
                    'path' => '/storage/' . $path,
                    'sort_order' => $order,
                ]);
                $order++;
            }
            $this->syncCoverFromGallery($game);
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Create Game',
            'description' => 'Created game: ' . $game->title,
        ]);

        return back()->with('success', 'Game created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'price' => 'required|integer|min:0',
            'release_date' => 'required|date',
            'developer' => 'required|string|max:100',
            'publisher_id' => 'required|exists:publishers,id',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $game = Game::findOrFail($id);
        $game->fill($request->only(
            'title', 'description', 'price', 'release_date', 'developer',
            'publisher_id', 'category_id', 'stock'
        ));
        $game->save();

        $files = $request->hasFile('images') ? $request->file('images') : [];

        if (count($files) > 0) {
            $lastOrder = GameImage::where('game_id', $game->id)->max('sort_order');
            $order = $lastOrder === null ? 0 : $lastOrder + 1;
            foreach ($files as $file) {
                $path = $file->store('games/gallery', 'public');
                GameImage::create([
                    'game_id' => $game->id,
                    'path' => '/storage/' . $path,
                    'sort_order' => $order,
                ]);
                $order++;
            }
            $this->syncCoverFromGallery($game);
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Update Game',
            'description' => 'Updated game: ' . $game->title,
        ]);

        return back()->with('success', 'Game updated successfully.');
    }

    /**
     * Tukar urutan dua gambar yang bertetangga (geser kiri/kanan).
     * direction: 'left' atau 'right'
     */
    public function moveImage(Request $request, $imageId)
    {
        $request->validate([
            'direction' => 'required|in:left,right',
        ]);

        $image = GameImage::findOrFail($imageId);
        $gameId = $image->game_id;

        $siblings = GameImage::where('game_id', $gameId)->orderBy('sort_order')->get();
        $currentIndex = $siblings->search(fn ($img) => $img->id === $image->id);

        $targetIndex = $request->direction === 'left' ? $currentIndex - 1 : $currentIndex + 1;

        if ($targetIndex < 0 || $targetIndex >= $siblings->count()) {
            return back(); // sudah di ujung, tidak ada yang ditukar
        }

        $target = $siblings[$targetIndex];

        // Tukar sort_order
        $tempOrder = $image->sort_order;
        $image->sort_order = $target->sort_order;
        $target->sort_order = $tempOrder;
        $image->save();
        $target->save();

        $this->syncCoverFromGallery($image->game);

        return back()->with('success', 'Image order updated.');
    }

    public function destroyImage($imageId)
    {
        $image = GameImage::findOrFail($imageId);
        $game = $image->game;
        $gameTitle = $game->title;
        $image->delete();

        $this->syncCoverFromGallery($game);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Delete Game Image',
            'description' => 'Deleted a gallery image from: ' . $gameTitle,
        ]);

        return back()->with('success', 'Image deleted.');
    }

    public function destroy($id)
    {
        $game = Game::findOrFail($id);
        $title = $game->title;
        $game->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Delete Game',
            'description' => 'Deleted game: ' . $title,
        ]);

        return back()->with('success', 'Game deleted successfully.');
    }

    /**
     * games.image selalu mengikuti gambar dengan sort_order terkecil di galeri.
     * Dipanggil setiap kali ada perubahan pada game_images (tambah, hapus, reorder).
     */
    private function syncCoverFromGallery(Game $game): void
    {
        $firstImage = GameImage::where('game_id', $game->id)
            ->orderBy('sort_order')
            ->first();

        $game->image = $firstImage ? $firstImage->path : '/images/games/default.jpg';
        $game->save();
    }
}