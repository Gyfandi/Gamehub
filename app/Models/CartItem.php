<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['cart_id', 'game_id'])]
class CartItem extends Model
{
    protected $table = 'cart_items';

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
