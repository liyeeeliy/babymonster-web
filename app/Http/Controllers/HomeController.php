<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\ForumPost;
use App\Models\Member;
use App\Models\News;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\VotePoll;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $latestNews = News::published()->with('author')->latest('published_at')->take(3)->get();
        $latestAlbums = Album::active()->latest('release_date')->take(4)->get();
        $members = Member::active()->get();

        $trendingPosts = ForumPost::with('user')
            ->withCount(['likes', 'comments'])
            ->orderByDesc('likes_count')
            ->take(3)
            ->get();

        $featuredPoll = VotePoll::with(['options' => fn ($q) => $q->orderByDesc('vote_count')])
            ->where('is_active', true)
            ->latest()
            ->first();

        $stats = [
            'total_fans' => User::count(),
            'albums_sold' => OrderItem::whereHas('order', fn ($q) => $q->whereIn('status', ['paid', 'process', 'shipped', 'delivered']))->sum('quantity'),
            'community_posts' => ForumPost::count(),
            'total_albums' => Album::active()->count(),
        ];

        return view('welcome', compact('latestNews', 'latestAlbums', 'members', 'trendingPosts', 'featuredPoll', 'stats'));
    }
}
