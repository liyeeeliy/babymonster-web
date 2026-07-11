<x-layouts.app title="Forum">
    <div class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
        {{-- ===================== HEADER ===================== --}}
        <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
            <div>
                <span class="section-kicker">Ngobrol Bareng</span>
                <h1 class="section-title">Forum MONBEBE 💬</h1>
                <p class="mt-2 max-w-xl text-slate-400">
                    Diskusi, teori, dan fangirling/fanboying seru bareng sesama fans BABYMONSTER! ✨
                </p>
            </div>
            @auth
                <a href="{{ route('forum.create') }}" class="btn-primary">✍️ Buat Post</a>
            @endauth
        </div>

        {{-- ===================== SORT TABS ===================== --}}
        @php $sort = request('sort'); @endphp
        <div class="mb-6 inline-flex gap-1 rounded-full border-2 border-white/10 bg-white/5 p-1">
            <a href="{{ route('forum.index') }}"
               @class([
                   'rounded-full px-4 py-1.5 text-sm font-semibold transition',
                   'bg-bm-purple text-white shadow-[var(--shadow-pop)]' => $sort !== 'trending',
                   'text-slate-400 hover:text-bm-purple' => $sort === 'trending',
               ])>🆕 Terbaru</a>
            <a href="{{ route('forum.index', ['sort' => 'trending']) }}"
               @class([
                   'rounded-full px-4 py-1.5 text-sm font-semibold transition',
                   'bg-bm-purple text-white shadow-[var(--shadow-pop)]' => $sort === 'trending',
                   'text-slate-400 hover:text-bm-purple' => $sort !== 'trending',
               ])>🔥 Trending</a>
        </div>

        {{-- ===================== LIST POST ===================== --}}
        @if ($posts->isEmpty())
            <x-empty-state emoji="💬" title="Belum ada postingan"
                           message="Forum masih sepi nih. Jadi yang pertama memulai obrolan seru!">
                @auth
                    <x-slot:action>
                        <a href="{{ route('forum.create') }}" class="btn-primary">✍️ Buat Post Pertama</a>
                    </x-slot:action>
                @endauth
            </x-empty-state>
        @else
            <div class="space-y-4">
                @foreach ($posts as $post)
                    <div class="card card-hover">
                        <div class="flex items-start gap-4">
                            {{-- Avatar --}}
                            <div class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-bm-purple/10 font-display font-bold text-bm-purple">
                                {{ mb_strtoupper(mb_substr($post->user->name ?? '?', 0, 1)) }}
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-semibold text-slate-400">
                                    {{ $post->user->name ?? 'MONBEBE' }} · {{ optional($post->created_at)->translatedFormat('d M Y') }}
                                </p>
                                <h3 class="mt-0.5">
                                    <a href="{{ route('forum.show', $post) }}"
                                       class="font-display text-lg font-bold text-bm-ink transition hover:text-bm-purple">
                                        {{ $post->title }}
                                    </a>
                                </h3>
                                <p class="mt-1 line-clamp-2 text-sm text-slate-400">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($post->content), 160) }}
                                </p>

                                {{-- Footer stats --}}
                                <div class="mt-3 flex flex-wrap items-center gap-4 text-sm font-semibold text-slate-400">
                                    <span>💜 {{ $post->likes_count }}</span>
                                    <span>💬 {{ $post->comments_count }}</span>
                                    <span>👁️ {{ $post->views }}</span>
                                    <a href="{{ route('forum.show', $post) }}"
                                       class="ml-auto text-bm-purple hover:underline">Baca →</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $posts->withQueryString()->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
