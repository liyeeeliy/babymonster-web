<?php

namespace App\Http\Controllers;

use App\Models\VoteOption;
use App\Models\VotePoll;
use App\Services\VoteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VotingController extends Controller
{
    public function __construct(private VoteService $voteService) {}

    public function index(Request $request): View
    {
        $polls = VotePoll::with('options')->where('is_active', true)->get()
            ->map(function ($poll) use ($request) {
                $poll->has_voted = $poll->userHasVoted($request->user()->id);
                return $poll;
            });

        return view('voting.index', compact('polls'));
    }

    public function vote(Request $request, VotePoll $votePoll): RedirectResponse
    {
        $data = $request->validate(['option_id' => 'required|exists:vote_options,id']);
        $option = VoteOption::findOrFail($data['option_id']);

        try {
            $this->voteService->cast($votePoll, $option, $request->user()->id);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Suaramu berhasil dicatat. Terima kasih sudah voting!');
    }

    public function results(VotePoll $votePoll): View
    {
        $results = $this->voteService->results($votePoll);
        return view('voting.results', compact('votePoll', 'results'));
    }
}
