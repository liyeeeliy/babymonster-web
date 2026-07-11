<x-layouts.app title="Pencarian">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        {{-- ===================== SEARCH BOX ===================== --}}
        <div class="mx-auto max-w-2xl text-center">
            <span class="section-kicker">Cari Apa Aja</span>
            <h1 class="section-title">Pencarian 🔍</h1>
            <form method="GET" action="{{ route('search') }}" class="mt-6 flex gap-2">
                <input type="text" name="q" value="{{ $q }}" autofocus
                       placeholder="Cari album, berita, member, atau topik forum..."
                       class="form-input flex-1 text-base">
                <button type="submit" class="btn-primary btn-lg shrink-0">Cari</button>
            </form>
        </div>

        @php
            $albums = $results['albums'] ?? collect();
            $newsItems = $results['news'] ?? collect();
            $members = $results['members'] ?? collect();
            $forum = $results['forum'] ?? collect();
            $allEmpty = $albums->isEmpty() && $newsItems->isEmpty() && $members->isEmpty() && $forum->isEmpty();
        @endphp

        {{-- ===================== KOSONG (belum mencari) ===================== --}}
        @if (empty($q))
            <div class="mt-12">
                <x-empty-state emoji="🔎" title="Mau cari apa, MONBEBE?"
                               message="Ketik kata kunci di atas untuk menemukan album, berita, member, atau obrolan forum favoritmu!" />
            </div>
        @elseif ($allEmpty)
            {{-- ===================== TIDAK ADA HASIL ===================== --}}
            <div class="mt-12">
                <x-empty-state emoji="🔍" title="Tidak ada hasil untuk “{{ $q }}”"
                               message="Coba kata kunci lain atau periksa ejaannya, ya!">
                    <x-slot:action>
                        <a href="{{ route('home') }}" class="btn-primary">← Kembali ke Beranda</a>
                    </x-slot:action>
                </x-empty-state>
            </div>
        @else
            <p class="mt-10 text-center text-sm font-semibold text-slate-400">
                Hasil pencarian untuk <span class="text-bm-purple">&quot;{{ $q }}&quot;</span> 💜
            </p>

            {{-- ===================== ALBUM ===================== --}}
            @if ($albums->isNotEmpty())
                <section class="mt-8">
                    <h2 class="mb-4 flex items-center gap-2 font-display text-xl font-bold text-bm-ink">
                        Album <span class="chip-yellow">{{ $albums->count() }}</span>
                    </h2>
                    <div class="grid grid-cols-2 gap-5 md:grid-cols-3 lg:grid-cols-4">
                        @foreach ($albums as $album)
                            <x-album-card :album="$album" />
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- ===================== BERITA ===================== --}}
            @if ($newsItems->isNotEmpty())
                <section class="mt-10">
                    <h2 class="mb-4 flex items-center gap-2 font-display text-xl font-bold text-bm-ink">
                        Berita <span class="chip-pink">{{ $newsItems->count() }}</span>
                    </h2>
                    <div class="grid gap-6 md:grid-cols-3">
                        @foreach ($newsItems as $item)
                            <x-news-card :news="$item" />
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- ===================== MEMBER ===================== --}}
            @if ($members->isNotEmpty())
                <section class="mt-10">
                    <h2 class="mb-4 flex items-center gap-2 font-display text-xl font-bold text-bm-ink">
                        Member <span class="chip-purple">{{ $members->count() }}</span>
                    </h2>
                    <div class="grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-4">
                        @foreach ($members as $m)
                            <div class="flex justify-center">
                                <x-member-card :member="$m" />
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- ===================== FORUM ===================== --}}
            @if ($forum->isNotEmpty())
                <section class="mt-10">
                    <h2 class="mb-4 flex items-center gap-2 font-display text-xl font-bold text-bm-ink">
                        Forum <span class="chip-mint">{{ $forum->count() }}</span>
                    </h2>
                    <div class="space-y-3">
                        @foreach ($forum as $post)
                            <a href="{{ route('forum.show', $post) }}" class="card card-hover flex items-center gap-4 !py-4">
                                <div class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-bm-purple/10 text-xl">💬</div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="truncate font-display font-bold text-bm-ink">{{ $post->title }}</h3>
                                    <p class="truncate text-sm text-slate-400">oleh {{ $post->user->name ?? 'MONBEBE' }}</p>
                                </div>
                                <span class="shrink-0 text-sm font-semibold text-bm-purple">Baca →</span>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        @endif
    </div>
</x-layouts.app>
