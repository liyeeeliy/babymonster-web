<?php

namespace App\Services;

use App\Models\VoteOption;
use App\Models\VotePoll;
use Illuminate\Support\Facades\DB;

class VoteService
{
    /** Cast satu suara. Satu akun hanya bisa satu suara per poll (dijaga unique constraint juga). */
    public function cast(VotePoll $poll, VoteOption $option, int $userId): void
    {
        if (!$poll->isOpen()) {
            throw new \RuntimeException('Voting untuk polling ini sudah ditutup.');
        }

        if ($poll->userHasVoted($userId)) {
            throw new \RuntimeException('Kamu sudah memberikan suara pada polling ini.');
        }

        DB::transaction(function () use ($poll, $option, $userId) {
            $poll->votes()->create([
                'vote_option_id' => $option->id,
                'user_id' => $userId,
            ]);
            $option->increment('vote_count');
        });
    }

    public function results(VotePoll $poll): array
    {
        return $poll->options()->get()->map(fn ($opt) => [
            'label' => $opt->label,
            'vote_count' => $opt->vote_count,
            'percentage' => $opt->percentage,
        ])->toArray();
    }
}
