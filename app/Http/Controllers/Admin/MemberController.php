<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(): View
    {
        $members = Member::orderBy('sort_order')->paginate(12);
        return view('admin.members.index', compact('members'));
    }

    public function create(): View
    {
        return view('admin.members.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['facts'] = $this->cleanFacts($request);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('members', 'public');
        }

        $member = Member::create($data);
        ActivityLog::record('member.create', "Menambah member \"{$member->name}\"", $member);

        return redirect()->route('admin.members.index')->with('success', 'Member berhasil ditambahkan.');
    }

    public function edit(Member $member): View
    {
        return view('admin.members.edit', compact('member'));
    }

    public function update(Request $request, Member $member): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['facts'] = $this->cleanFacts($request);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('members', 'public');
        }

        $member->update($data);
        ActivityLog::record('member.update', "Memperbarui member \"{$member->name}\"", $member);

        return redirect()->route('admin.members.index')->with('success', 'Member berhasil diperbarui.');
    }

    public function destroy(Member $member): RedirectResponse
    {
        $name = $member->name;
        $member->delete();
        ActivityLog::record('member.delete', "Menghapus member \"{$name}\"");

        return redirect()->route('admin.members.index')->with('success', 'Member berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:100',
            'birth_date' => 'nullable|date',
            'position' => 'nullable|string|max:100',
            'nationality' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:4096',
            'sort_order' => 'nullable|integer|min:0',
        ]);
    }

    /** Ubah textarea facts (satu fakta per baris) menjadi array bersih. */
    private function cleanFacts(Request $request): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $request->input('facts_text', '')))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}
