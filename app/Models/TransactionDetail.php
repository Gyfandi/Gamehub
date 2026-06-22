<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['transaction_id', 'game_id', 'price'])]
class TransactionDetail extends Model
{
    protected $table = 'transaction_details';

    protected function casts(): array
    {
        return [
            'price' => 'integer',
        ];
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
