<x-layouts.admin title="Kelola Member">
    {{-- Header --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <span class="section-kicker">Profil Grup</span>
            <h2 class="font-display text-2xl font-bold text-bm-ink">Kelola Member 🎤</h2>
        </div>
        <a href="{{ route('admin.members.create') }}" class="btn-primary">➕ Tambah Member</a>
    </div>

    @if ($members->isEmpty())
        <x-empty-state emoji="🎤" title="Belum ada member" message="Tambahkan profil member BABYMONSTER di sini!">
            <x-slot:action>
                <a href="{{ route('admin.members.create') }}" class="btn-primary">➕ Tambah Member</a>
            </x-slot:action>
        </x-empty-state>
    @else
        <div class="card !p-0">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-white/10 text-xs font-semibold uppercase tracking-wide text-slate-400">
                            <th class="px-5 py-3">Foto</th>
                            <th class="px-5 py-3">Nama</th>
                            <th class="px-5 py-3">Posisi</th>
                            <th class="px-5 py-3">Kebangsaan</th>
                            <th class="px-5 py-3">Urutan</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-bm-purple/5">
                        @foreach ($members as $m)
                            <tr class="transition hover:bg-bm-purple/5">
                                <td class="px-5 py-3">
                                    <x-cover :src="$m->photo" :label="$m->name" emoji="🎤" rounded="rounded-2xl" class="h-12 w-12" />
                                </td>
                                <td class="px-5 py-3 font-semibold text-bm-ink">{{ $m->name }}</td>
                                <td class="px-5 py-3 text-slate-300">{{ $m->position ?? '—' }}</td>
                                <td class="px-5 py-3 text-slate-300">{{ $m->nationality ?? '—' }}</td>
                                <td class="px-5 py-3"><span class="chip-slate">#{{ $m->sort_order }}</span></td>
                                <td class="px-5 py-3">
                                    @if ($m->is_active)
                                        <span class="chip-mint">Aktif</span>
                                    @else
                                        <span class="chip-slate">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.members.edit', $m) }}" class="btn-outline btn-sm">✏️ Edit</a>
                                        <form method="POST" action="{{ route('admin.members.destroy', $m) }}"
                                              onsubmit="return confirm('Yakin ingin menghapus member ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-sm btn bg-rose-100 text-rose-600 hover:bg-rose-200">🗑️ Hapus</button>
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
            {{ $members->links() }}
        </div>
    @endif
</x-layouts.admin>
