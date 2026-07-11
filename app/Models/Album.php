<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'cover', 'price', 'stock', 'description',
        'tracklist', 'release_date', 'preview_audio', 'track_audio', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'tracklist' => 'array',
            'track_audio' => 'array',
            'release_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function orderItems()
    {
        return $this->morphMany(OrderItem::class, 'purchasable');
    }

    public function carts()
    {
        return $this->morphMany(Cart::class, 'purchasable');
    }

    public function reviews()
    {
        return $this->morphMany(ProductReview::class, 'reviewable');
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $term)
    {
        return $term ? $query->where('name', 'like', "%{$term}%") : $query;
    }

    public function scopePriceBetween($query, $min, $max)
    {
        if ($min) $query->where('price', '>=', $min);
        if ($max) $query->where('price', '<=', $max);
        return $query;
    }
}
