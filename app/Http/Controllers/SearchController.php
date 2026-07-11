<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\ForumPost;
use App\Models\Member;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    /** Pencarian global: album, berita, member, dan post forum sekaligus. */
    public function index(Request $request): View|\Illuminate\Http\JsonResponse
    {
        $q = trim((string) $request->get('q', ''));

        $results = [
            'albums' => $q ? Album::active()->search($q)->take(5)->get() : collect(),
            'news' => $q ? News::published()->search($q)->take(5)->get() : collect(),
            'members' => $q ? Member::active()->where('name', 'like', "%{$q}%")->take(5)->get() : collect(),
            'forum' => $q ? ForumPost::with('user')->where('title', 'like', "%{$q}%")->take(5)->get() : collect(),
        ];

        if ($request->wantsJson()) {
            return response()->json($results);
        }

        return view('home.search-results', ['q' => $q, 'results' => $results]);
    }
}
