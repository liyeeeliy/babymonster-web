<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VotePoll extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'type', 'starts_at', 'ends_at', 'is_active'];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function options()
    {
        return $this->hasMany(VoteOption::class)->orderByDesc('vote_count');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function isOpen(): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && now()->lt($this->starts_at)) return false;
        if ($this->ends_at && now()->gt($this->ends_at)) return false;
        return true;
    }

    public function userHasVoted($userId): bool
    {
        return $this->votes()->where('user_id', $userId)->exists();
    }
}
