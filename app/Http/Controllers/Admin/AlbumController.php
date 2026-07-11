<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Album;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AlbumController extends Controller
{
    public function index(): View
    {
        $albums = Album::latest()->paginate(12);
        return view('admin.albums.index', compact('albums'));
    }

    public function create(): View
    {
        return view('admin.albums.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']) . '-' . Str::lower(Str::random(5));
        $data['is_active'] = $request->boolean('is_active');
        $data['tracklist'] = $this->cleanTracklist($request);

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('albums', 'public');
        }
        if ($request->hasFile('preview_audio')) {
            $data['preview_audio'] = $request->file('preview_audio')->store('albums/preview', 'public');
        }

        $album = Album::create($data);
        ActivityLog::record('album.create', "Menambah album \"{$album->name}\"", $album);

        return redirect()->route('admin.albums.index')->with('success', "Album \"{$album->name}\" berhasil ditambahkan.");
    }

    public function edit(Album $album): View
    {
        return view('admin.albums.edit', compact('album'));
    }

    public function update(Request $request, Album $album): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['tracklist'] = $this->cleanTracklist($request);

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('albums', 'public');
        }
        if ($request->hasFile('preview_audio')) {
            $data['preview_audio'] = $request->file('preview_audio')->store('albums/preview', 'public');
        }

        $album->update($data);
        ActivityLog::record('album.update', "Memperbarui album \"{$album->name}\"", $album);

        return redirect()->route('admin.albums.index')->with('success', 'Album berhasil diperbarui.');
    }

    public function destroy(Album $album): RedirectResponse
    {
        $name = $album->name;
        $album->delete();
        ActivityLog::record('album.delete', "Menghapus album \"{$name}\"");

        return redirect()->route('admin.albums.index')->with('success', 'Album berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'release_date' => 'nullable|date',
            'cover' => 'nullable|image|max:4096',
            'preview_audio' => 'nullable|mimes:mp3,wav,ogg|max:16384',
        ]);
    }

    /** Ubah textarea tracklist (satu judul per baris) menjadi array bersih. */
    private function cleanTracklist(Request $request): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $request->input('tracklist_text', '')))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}
