<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = ['vote_poll_id', 'vote_option_id', 'user_id'];

    public function poll()
    {
        return $this->belongsTo(VotePoll::class, 'vote_poll_id');
    }

    public function option()
    {
        return $this->belongsTo(VoteOption::class, 'vote_option_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
