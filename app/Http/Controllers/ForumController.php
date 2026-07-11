<?php

namespace App\Http\Controllers;

use App\Models\ForumPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ForumController extends Controller
{
    public function index(Request $request): View
    {
        $query = ForumPost::with('user')->withCount(['likes', 'comments']);

        $posts = $request->get('sort') === 'trending'
            ? $query->orderByDesc('likes_count')->paginate(10)
            : $query->latest()->paginate(10);

        return view('forum.index', compact('posts'));
    }

    public function show(ForumPost $forumPost): View
    {
        $forumPost->increment('views');
        $forumPost->load(['user', 'comments.user', 'comments.replies.user']);

        return view('forum.show', compact('forumPost'));
    }

    public function create(): View
    {
        return view('forum.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'photo' => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('forum', 'public');
        }

        $post = $request->user()->forumPosts()->create($data);

        return redirect()->route('forum.show', $post)->with('success', 'Post berhasil dibuat.');
    }

    public function share(ForumPost $forumPost): RedirectResponse
    {
        $forumPost->increment('share_count');
        return back()->with('success', 'Link post berhasil disalin.');
    }

    public function storeComment(Request $request, ForumPost $forumPost): RedirectResponse
    {
        $data = $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $forumPost->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $data['content'],
            'parent_id' => $data['parent_id'] ?? null,
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    public function destroy(Request $request, ForumPost $forumPost): RedirectResponse
    {
        abort_if($forumPost->user_id !== $request->user()->id && !$request->user()->isAdmin(), 403);
        $forumPost->delete();

        return redirect()->route('forum.index')->with('success', 'Post berhasil dihapus.');
    }
}
