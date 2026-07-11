<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlbumController extends Controller
{
    public function index(Request $request): View
    {
        $albums = Album::active()
            ->search($request->get('q'))
            ->priceBetween($request->get('min_price'), $request->get('max_price'))
            ->latest('release_date')
            ->paginate(12)
            ->withQueryString();

        return view('store.index', compact('albums'));
    }

    public function show(Album $album): View
    {
        $album->load(['reviews.user']);
        return view('store.show', compact('album'));
    }

    public function storeReview(Request $request, Album $album): RedirectResponse
    {
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $album->reviews()->updateOrCreate(
            ['user_id' => $request->user()->id],
            $data
        );

        return back()->with('success', 'Terima kasih atas ulasannya!');
    }
}
