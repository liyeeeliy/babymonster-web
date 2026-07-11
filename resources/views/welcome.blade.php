<x-layouts.app>
    {{-- ===================== HERO ===================== --}}
    <section class="relative -mt-16 overflow-hidden px-5 pb-20 pt-24 sm:px-8 lg:pt-28">
        {{-- blob glow --}}
        <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
            <div class="animate-float absolute -left-24 -top-24 h-[420px] w-[420px] rounded-full bg-bm-violet/25 blur-[100px]"></div>
            <div class="animate-float absolute -right-10 top-4 h-[420px] w-[420px] rounded-full bg-bm-pink/25 blur-[100px]" style="animation-delay:1.5s"></div>
        </div>

        {{-- Teks hero --}}
        <div class="relative mx-auto max-w-4xl text-center">
            <span class="mb-4 inline-block font-mono text-xs uppercase tracking-[0.3em] text-bm-lavender">Comeback · Juli 2026</span>
            <h1 class="font-display text-5xl font-black uppercase leading-[1.05] text-white sm:text-7xl">
                We Go Up,<br><span class="text-gradient">We Go Monster</span>
            </h1>
            <p class="mx-auto mt-5 max-w-md text-sm text-bm-muted">
                Portal resmi penggemar BABYMONSTER — berita, album, merchandise, komunitas, dan voting dalam satu tempat.
            </p>
            <div class="mt-7 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('store.index') }}" class="btn-primary">Jelajahi Album</a>
                <a href="{{ route('members.index') }}" class="btn-outline">Kenali Member</a>
            </div>
        </div>

        {{-- Banner comeback --}}
        @php $featuredAlbum = $latestAlbums->first(); @endphp
        @if ($featuredAlbum)
            <div class="grad-border glass relative mx-auto mt-16 flex max-w-5xl flex-col items-center gap-6 rounded-3xl p-8 sm:flex-row sm:p-10">
                <div class="relative flex h-40 w-full shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-gradient-to-br from-bm-purple to-bm-pink text-center font-display text-2xl font-black leading-tight text-white sm:w-40">
                    <img src="{{ asset('img/CHOOM.jpg') }}" alt="{{ $featuredAlbum->name }}"
                         class="absolute inset-0 h-full w-full object-cover object-top" onerror="this.remove()">
                    <span>NEW<br>ERA</span>
                </div>
                <div class="flex-1 text-center sm:text-left">
                    <p class="mb-1 font-mono text-xs uppercase tracking-wide text-bm-lavender">Album Comeback Terbaru</p>
                    <h3 class="font-display text-2xl font-bold text-white">{{ $featuredAlbum->name }}</h3>
                    <p class="mt-1 text-sm text-bm-muted">Pre-order dibuka sekarang. Termasuk photocard random &amp; poster edisi terbatas.</p>
                </div>
                <a href="{{ route('store.show', $featuredAlbum) }}" class="btn-primary shrink-0">Pre-Order</a>
            </div>
        @endif
    </section>

    {{-- ===================== STATS ===================== --}}
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <x-stat-card emoji="🧑‍🤝‍🧑" :value="number_format($stats['total_fans'])" label="Total Fans" tone="purple" />
            <x-stat-card emoji="💿" :value="number_format($stats['albums_sold'])" label="Album Terjual" tone="pink" />
            <x-stat-card emoji="💬" :value="number_format($stats['community_posts'])" label="Postingan Forum" tone="mint" />
            <x-stat-card emoji="🎶" :value="number_format($stats['total_albums'])" label="Katalog Album" tone="yellow" />
        </div>
    </section>

    {{-- ===================== ALBUM TERBARU ===================== --}}
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-end justify-between">
            <div>
                <span class="section-kicker">Baru Rilis</span>
                <h2 class="section-title">Album Terbaru 💿</h2>
            </div>
            <a href="{{ route('store.index') }}" class="btn-outline btn-sm hidden sm:inline-flex">Lihat Semua →</a>
        </div>

        @if ($latestAlbums->isEmpty())
            <x-empty-state emoji="💿" title="Belum ada album" message="Nantikan rilisan album berikutnya!" />
        @else
            <div class="grid grid-cols-2 gap-5 md:grid-cols-4">
                @foreach ($latestAlbums as $album)
                    <x-album-card :album="$album" />
                @endforeach
            </div>
        @endif
    </section>

    {{-- ===================== MEMBER ===================== --}}
    @if ($members->isNotEmpty())
        <section class="bg-white/5 py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-8 text-center">
                    <span class="section-kicker">Meet The Members</span>
                    <h2 class="section-title">Kenalan sama Member BABYMON 🎤</h2>
                </div>
                <div class="no-scrollbar flex gap-4 overflow-x-auto pb-2 sm:grid sm:grid-cols-4 sm:overflow-visible lg:grid-cols-7">
                    @foreach ($members as $member)
                        <x-member-card :member="$member" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===================== VOTING TEASER ===================== --}}
    @if ($featuredPoll)
        @php $totalVotes = max(1, $featuredPoll->options->sum('vote_count')); @endphp
        <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-[2rem] bg-gradient-to-br from-bm-purple to-bm-pink p-8 text-white shadow-[var(--shadow-pop)] sm:p-12">
                <div class="grid gap-8 lg:grid-cols-2">
                    <div>
                        <span class="chip bg-white/20 text-white">🗳️ Voting Aktif</span>
                        <h2 class="mt-3 font-display text-3xl font-bold">{{ $featuredPoll->title }}</h2>
                        <p class="mt-2 text-white/80">Dukung member favoritmu sekarang! Setiap fans dapat 1 suara.</p>
                        <a href="{{ route('voting.index') }}" class="btn-yellow btn-lg mt-6">Vote Sekarang →</a>
                    </div>
                    <div class="space-y-3">
                        @foreach ($featuredPoll->options->take(3) as $opt)
                            @php $pct = round($opt->vote_count / $totalVotes * 100); @endphp
                            <div>
                                <div class="mb-1 flex justify-between text-sm font-semibold">
                                    <span>{{ $opt->label }}</span><span>{{ $pct }}%</span>
                                </div>
                                <div class="h-2.5 overflow-hidden rounded-full bg-white/20">
                                    <div class="h-full rounded-full bg-bm-yellow" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ===================== BERITA ===================== --}}
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-end justify-between">
            <div>
                <span class="section-kicker">Update Terbaru</span>
                <h2 class="section-title">Kabar BABYMONSTER 📰</h2>
            </div>
            <a href="{{ route('news.index') }}" class="btn-outline btn-sm hidden sm:inline-flex">Semua Berita →</a>
        </div>

        @if ($latestNews->isEmpty())
            <x-empty-state emoji="📰" title="Belum ada berita" />
        @else
            <div class="grid gap-6 md:grid-cols-3">
                @foreach ($latestNews as $news)
                    <x-news-card :news="$news" />
                @endforeach
            </div>
        @endif
    </section>

    {{-- ===================== FORUM TRENDING ===================== --}}
    @if ($trendingPosts->isNotEmpty())
        <section class="bg-white/5 py-16">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <div class="mb-8 text-center">
                    <span class="section-kicker">Lagi Rame</span>
                    <h2 class="section-title">Obrolan di Forum 💬</h2>
                </div>
                <div class="space-y-3">
                    @foreach ($trendingPosts as $post)
                        <a href="{{ route('forum.show', $post) }}" class="card card-hover flex items-center gap-4 !py-4">
                            <div class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-bm-purple/10 text-xl">💬</div>
                            <div class="min-w-0 flex-1">
                                <h3 class="truncate font-display font-bold text-bm-ink">{{ $post->title }}</h3>
                                <p class="truncate text-sm text-slate-400">oleh {{ $post->user->name }}</p>
                            </div>
                            <div class="flex shrink-0 gap-3 text-sm font-semibold text-slate-400">
                                <span>💜 {{ $post->likes_count }}</span>
                                <span>💬 {{ $post->comments_count }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-6 text-center">
                    <a href="{{ route('forum.index') }}" class="btn-primary">Buka Forum →</a>
                </div>
            </div>
        </section>
    @endif

    {{-- ===================== CTA JOIN ===================== --}}
    @guest
        <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="rounded-[2rem] border border-white/10 bg-gradient-to-br from-[#1b1136] to-[#0d0a1c] p-10 text-center text-white sm:p-16">
                <div class="mb-3 text-5xl animate-float">👾</div>
                <h2 class="font-display text-3xl font-bold sm:text-4xl">Jadi bagian dari MONBEBE!</h2>
                <p class="mx-auto mt-3 max-w-lg text-white/70">
                    Daftar gratis untuk beli album, ikut voting, dan ngobrol bareng fans lainnya.
                </p>
                <a href="{{ route('register') }}" class="btn-yellow btn-lg mt-6">Daftar Sekarang</a>
            </div>
        </section>
    @endguest
</x-layouts.app>
