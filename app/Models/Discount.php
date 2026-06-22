<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['game_id', 'percentage', 'start_date', 'end_date'])]
class Discount extends Model
{
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'percentage' => 'integer',
        ];
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
