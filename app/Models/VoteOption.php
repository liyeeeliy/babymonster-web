<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoteOption extends Model
{
    use HasFactory;

    protected $fillable = ['vote_poll_id', 'label', 'image', 'vote_count'];

    public function poll()
    {
        return $this->belongsTo(VotePoll::class, 'vote_poll_id');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function getPercentageAttribute(): float
    {
        $total = $this->poll->options()->sum('vote_count');
        return $total > 0 ? round(($this->vote_count / $total) * 100, 1) : 0;
    }
}
