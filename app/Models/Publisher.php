<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'description'])]
class Publisher extends Model
{
    public function games()
    {
        return $this->hasMany(Game::class);
    }
}
