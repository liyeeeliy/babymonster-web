<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user()->load('favoriteMember');
        $purchasedAlbums = $user->purchasedAlbums()->get();
        $recentOrders = $user->orders()->latest()->take(5)->get();
        $members = \App\Models\Member::active()->get(); // untuk dropdown "member favorit"

        return view('home.profile', compact('user', 'purchasedAlbums', 'recentOrders', 'members'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|alpha_dash|unique:users,username,' . $request->user()->id,
            'email' => 'required|email|unique:users,email,' . $request->user()->id,
            'bio' => 'nullable|string|max:500',
            'favorite_member_id' => 'nullable|exists:members,id',
            'avatar' => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $request->user()->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|confirmed|min:8',
        ]);

        $request->user()->update(['password' => Hash::make($data['password'])]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
