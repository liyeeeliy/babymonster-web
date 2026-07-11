<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    protected $fillable = [
        'title', 'slug', 'thumbnail', 'content', 'excerpt',
        'category', 'author_id', 'published_at', 'views', 'status',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /** Estimasi waktu baca dalam menit (asumsi 200 kata/menit, minimal 1). */
    public function getReadingTimeAttribute(): int
    {
        $text = trim(strip_tags((string) $this->content));

        if ($text === '') {
            return 1;
        }

        $words = count(preg_split('/\s+/u', $text) ?: []);

        return max(1, (int) ceil($words / 200));
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }

    public function scopeCategory($query, $category)
    {
        return $category ? $query->where('category', $category) : $query;
    }

    public function scopeSearch($query, $term)
    {
        return $term ? $query->where('title', 'like', "%{$term}%") : $query;
    }
}
