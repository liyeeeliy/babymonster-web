<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::when($request->get('role'), fn ($q, $r) => $q->where('role', $r))
            ->when($request->get('q'), fn ($q, $s) => $q->where(function ($q2) use ($s) {
                $q2->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%");
            }))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        // Cegah admin mengunci dirinya sendiri keluar dari panel.
        // Karena rute ini hanya bisa diakses admin, melarang aksi pada diri sendiri
        // sudah menjamin selalu tersisa minimal satu admin aktif.
        if ($user->is($request->user())) {
            return back()->with('error', 'Kamu tidak bisa mengubah peranmu sendiri.');
        }

        $data = $request->validate(['role' => 'required|in:admin,staff,member']);

        // 'role' sengaja tidak mass-assignable (lihat User::$fillable), jadi
        // $user->update([...]) akan membuangnya diam-diam. Set eksplisit.
        $user->role = $data['role'];
        $user->save();

        return back()->with('success', 'Role user berhasil diperbarui.');
    }

    public function toggleActive(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Kamu tidak bisa menonaktifkan akunmu sendiri.');
        }

        // Sama seperti 'role': 'is_active' tidak mass-assignable.
        $user->is_active = !$user->is_active;
        $user->save();

        return back()->with('success', 'Status akun berhasil diperbarui.');
    }
}
