<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumPost extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'content', 'photo', 'share_count', 'views'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function getLikeCountAttribute(): int
    {
        return $this->likes()->count();
    }

    public function scopeTrending($query)
    {
        return $query->withCount('likes')->orderByDesc('likes_count');
    }
}
