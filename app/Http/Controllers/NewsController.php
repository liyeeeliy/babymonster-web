<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $news = News::published()
            ->with('author')
            ->category($request->get('category'))
            ->search($request->get('q'))
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('news.index', compact('news'));
    }

    public function show(News $news): View
    {
        abort_if($news->status !== 'published', 404);

        $news->increment('views');
        $news->load(['author', 'comments.user', 'comments.replies.user']);

        return view('news.show', compact('news'));
    }

    public function storeComment(Request $request, News $news): RedirectResponse
    {
        $data = $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $news->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $data['content'],
            'parent_id' => $data['parent_id'] ?? null,
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }
}
