<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\News;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(): View
    {
        $news = News::with('author')->latest()->paginate(12);
        return view('admin.news.index', compact('news'));
    }

    public function create(): View
    {
        return view('admin.news.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['title']) . '-' . Str::lower(Str::random(5));
        $data['author_id'] = $request->user()->id;
        $data['published_at'] = $data['status'] === 'published' ? now() : null;

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('news', 'public');
        }

        $news = News::create($data);
        ActivityLog::record('news.create', "Menulis berita \"{$news->title}\"", $news);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil disimpan.');
    }

    public function edit(News $news): View
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news): RedirectResponse
    {
        $data = $this->validated($request);

        if ($data['status'] === 'published' && $news->status !== 'published') {
            $data['published_at'] = now();
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('news', 'public');
        }

        $news->update($data);
        ActivityLog::record('news.update', "Memperbarui berita \"{$news->title}\"", $news);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(News $news): RedirectResponse
    {
        $title = $news->title;
        $news->delete();
        ActivityLog::record('news.delete', "Menghapus berita \"{$title}\"");

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|max:4096',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category' => 'required|string|max:100',
            'status' => 'required|in:draft,published',
        ]);
    }
}
