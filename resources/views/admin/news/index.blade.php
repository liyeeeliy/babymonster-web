<x-layouts.admin title="Kelola Berita">
    {{-- Header --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <span class="section-kicker">Redaksi</span>
            <h2 class="font-display text-2xl font-bold text-bm-ink">Kelola Berita 📰</h2>
        </div>
        <a href="{{ route('admin.news.create') }}" class="btn-primary">✍️ Tulis Berita</a>
    </div>

    @if ($news->isEmpty())
        <x-empty-state emoji="📰" title="Belum ada berita" message="Bagikan kabar terbaru BABYMONSTER ke MONBEBE!">
            <x-slot:action>
                <a href="{{ route('admin.news.create') }}" class="btn-primary">✍️ Tulis Berita</a>
            </x-slot:action>
        </x-empty-state>
    @else
        <div class="card !p-0">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-white/10 text-xs font-semibold uppercase tracking-wide text-slate-400">
                            <th class="px-5 py-3">Judul</th>
                            <th class="px-5 py-3">Kategori</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Penulis</th>
                            <th class="px-5 py-3">Terbit</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-bm-purple/5">
                        @foreach ($news as $n)
                            <tr class="transition hover:bg-bm-purple/5">
                                <td class="px-5 py-3">
                                    <p class="line-clamp-1 max-w-xs font-semibold text-bm-ink">{{ $n->title }}</p>
                                    <p class="text-xs text-slate-400">👁️ {{ number_format($n->views) }} dilihat</p>
                                </td>
                                <td class="px-5 py-3"><span class="chip-pink">{{ $n->category }}</span></td>
                                <td class="px-5 py-3">
                                    @if ($n->status === 'published')
                                        <span class="chip-mint">Terbit</span>
                                    @else
                                        <span class="chip-yellow">Draft</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-slate-300">{{ $n->author?->name ?? '—' }}</td>
                                <td class="px-5 py-3 text-slate-400">{{ optional($n->published_at)->translatedFormat('d M Y') ?? '—' }}</td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.news.edit', $n) }}" class="btn-outline btn-sm">✏️ Edit</a>
                                        <form method="POST" action="{{ route('admin.news.destroy', $n) }}"
                                              onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
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
            {{ $news->links() }}
        </div>
    @endif
</x-layouts.admin>
