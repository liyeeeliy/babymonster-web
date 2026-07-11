<x-layouts.admin title="Kelola Album">
    {{-- Header --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <span class="section-kicker">Katalog Toko</span>
            <h2 class="font-display text-2xl font-bold text-bm-ink">Kelola Album 💿</h2>
        </div>
        <a href="{{ route('admin.albums.create') }}" class="btn-primary">➕ Tambah Album</a>
    </div>

    @if ($albums->isEmpty())
        <x-empty-state emoji="💿" title="Belum ada album" message="Yuk tambahkan album pertama untuk MONBEBE!">
            <x-slot:action>
                <a href="{{ route('admin.albums.create') }}" class="btn-primary">➕ Tambah Album</a>
            </x-slot:action>
        </x-empty-state>
    @else
        <div class="card !p-0">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-white/10 text-xs font-semibold uppercase tracking-wide text-slate-400">
                            <th class="px-5 py-3">Cover</th>
                            <th class="px-5 py-3">Nama Album</th>
                            <th class="px-5 py-3">Harga</th>
                            <th class="px-5 py-3">Stok</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-bm-purple/5">
                        @foreach ($albums as $album)
                            <tr class="transition hover:bg-bm-purple/5">
                                <td class="px-5 py-3">
                                    <x-cover :src="$album->cover" :label="$album->name" emoji="💿" rounded="rounded-2xl" class="h-12 w-12" />
                                </td>
                                <td class="px-5 py-3">
                                    <p class="font-semibold text-bm-ink">{{ $album->name }}</p>
                                    <p class="text-xs text-slate-400">{{ optional($album->release_date)->translatedFormat('d M Y') ?? 'Tanpa tanggal rilis' }}</p>
                                </td>
                                <td class="px-5 py-3 font-semibold text-bm-purple">Rp{{ number_format($album->price, 0, ',', '.') }}</td>
                                <td class="px-5 py-3">
                                    @if ($album->stock <= 0)
                                        <span class="chip bg-rose-100 text-rose-600">Habis</span>
                                    @else
                                        <span class="chip-slate">{{ number_format($album->stock) }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    @if ($album->is_active)
                                        <span class="chip-mint">Aktif</span>
                                    @else
                                        <span class="chip-slate">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.albums.edit', $album) }}" class="btn-outline btn-sm">✏️ Edit</a>
                                        <form method="POST" action="{{ route('admin.albums.destroy', $album) }}"
                                              onsubmit="return confirm('Yakin ingin menghapus album ini?')">
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
            {{ $albums->links() }}
        </div>
    @endif
</x-layouts.admin>
