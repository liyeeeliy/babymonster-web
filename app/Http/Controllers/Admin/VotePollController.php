<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VotePoll;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VotePollController extends Controller
{
    public function index(): View
    {
        $polls = VotePoll::withCount('votes')->latest()->get();
        return view('admin.voting.index', compact('polls'));
    }

    public function create(): View
    {
        return view('admin.voting.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:favorite_member,best_performance,favorite_song',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'options' => 'required|array|min:2',
            'options.*.label' => 'required|string|max:255',
        ]);

        $poll = VotePoll::create([
            'title' => $data['title'],
            'type' => $data['type'],
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
        ]);

        foreach ($data['options'] as $opt) {
            $poll->options()->create(['label' => $opt['label']]);
        }

        return redirect()->route('admin.voting.index')->with('success', 'Polling voting berhasil dibuat.');
    }

    public function destroy(VotePoll $votePoll): RedirectResponse
    {
        $votePoll->delete();
        return back()->with('success', 'Polling berhasil dihapus.');
    }
}
