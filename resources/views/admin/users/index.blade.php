<x-layouts.admin title="Kelola User">
    @php
        $roleLabels = [
            'admin'  => 'Admin',
            'staff'  => 'Staff',
            'member' => 'Member',
        ];
        $roleChips = [
            'admin'  => 'chip-purple',
            'staff'  => 'chip-pink',
            'member' => 'chip-slate',
        ];
        $curRole = request('role', '');
    @endphp

    {{-- Header --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <span class="section-kicker">Komunitas MONBEBE</span>
            <h2 class="font-display text-2xl font-bold text-bm-ink">Kelola User 👥</h2>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('admin.users.index') }}" class="card mb-6 flex flex-col gap-3 sm:flex-row sm:items-end">
        <div class="flex-1">
            <label for="q" class="form-label">Cari User</label>
            <input type="text" id="q" name="q" class="form-input" value="{{ request('q') }}"
                   placeholder="Nama, username, atau email...">
        </div>
        <div class="sm:w-52">
            <label for="role" class="form-label">Peran</label>
            <select id="role" name="role" class="form-select">
                <option value="">Semua Peran</option>
                @foreach ($roleLabels as $key => $label)
                    <option value="{{ $key }}" {{ $curRole === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-primary">🔍 Cari</button>
    </form>

    @if ($users->isEmpty())
        <x-empty-state emoji="👥" title="User tidak ditemukan" message="Coba ubah kata kunci atau filter peran." />
    @else
        <div class="card !p-0">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[860px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-white/10 text-xs font-semibold uppercase tracking-wide text-slate-400">
                            <th class="px-5 py-3">User</th>
                            <th class="px-5 py-3">Email</th>
                            <th class="px-5 py-3">Peran</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Bergabung</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-bm-purple/5">
                        @foreach ($users as $user)
                            <tr class="transition hover:bg-bm-purple/5">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-bm-purple font-bold text-white">
                                            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                        </span>
                                        <div>
                                            <p class="font-semibold text-bm-ink">{{ $user->name }}</p>
                                            <p class="text-xs text-slate-400">&#64;{{ $user->username }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-slate-300">{{ $user->email }}</td>
                                <td class="px-5 py-3">
                                    <span class="{{ $roleChips[$user->role] ?? 'chip-slate' }}">{{ $roleLabels[$user->role] ?? ucfirst($user->role) }}</span>
                                </td>
                                <td class="px-5 py-3">
                                    @if ($user->is_active)
                                        <span class="chip-mint">Aktif</span>
                                    @else
                                        <span class="chip bg-rose-100 text-rose-600">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-slate-400">{{ optional($user->created_at)->translatedFormat('d M Y') }}</td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- Ubah peran --}}
                                        <form method="POST" action="{{ route('admin.users.role', $user) }}">
                                            @csrf
                                            @method('PATCH')
                                            <select name="role" onchange="this.form.submit()"
                                                    class="form-select !py-1.5 !text-xs">
                                                @foreach ($roleLabels as $key => $label)
                                                    <option value="{{ $key }}" {{ $user->role === $key ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </form>
                                        {{-- Aktif/Nonaktif --}}
                                        <form method="POST" action="{{ route('admin.users.toggle', $user) }}"
                                              onsubmit="return confirm('Yakin ingin mengubah status akun user ini?')">
                                            @csrf
                                            @method('PATCH')
                                            @if ($user->is_active)
                                                <button type="submit" class="btn-sm btn bg-rose-100 text-rose-600 hover:bg-rose-200">🚫 Nonaktifkan</button>
                                            @else
                                                <button type="submit" class="btn-sm btn bg-emerald-100 text-emerald-600 hover:bg-emerald-200">✅ Aktifkan</button>
                                            @endif
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8">
            {{ $users->appends(request()->query())->links() }}
        </div>
    @endif
</x-layouts.admin>
