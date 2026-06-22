<?php

namespace App\Models;

use App\Models\GameImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'title', 'description', 'price', 'release_date', 'image',
    'developer', 'publisher_id', 'category_id', 'stock', 'rating'
])]
class Game extends Model
{
    protected function casts(): array
    {
        return [
            'release_date' => 'date',
            'price' => 'integer',
            'stock' => 'integer',
        ];
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function images()
    {
    return $this->hasMany(GameImage::class)->orderBy('sort_order');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    public function activeDiscount()
    {
        return $this->hasOne(Discount::class)
            ->where('start_date', '<=', now()->toDateString())
            ->where('end_date', '>=', now()->toDateString());
    }

    public function getFinalPriceAttribute()
    {
        $discount = $this->activeDiscount;
        if ($discount) {
            return $this->price * (1 - $discount->percentage / 100);
        }
        return $this->price;
    }

    public function getIsDiscountedAttribute()
    {
        return $this->activeDiscount !== null;
    }

    public function getPositiveReviewPercentageAttribute()
    {
        $total = $this->reviews()->count();
        if ($total === 0) {
            return null; // Return null to indicate no reviews yet
        }
        $recommended = $this->reviews()->where('recommendation', true)->count();
        return (int) round(($recommended / $total) * 100);
    }
}
