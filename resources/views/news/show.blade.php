<x-layouts.app :title="$news->title">
    <article class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
        {{-- ===================== BACK LINK ===================== --}}
        <a href="{{ route('news.index') }}"
           class="mb-6 inline-flex items-center gap-2 text-sm font-semibold text-slate-400 hover:text-bm-purple">
            ← Kembali ke Berita
        </a>

        {{-- ===================== HEADER ===================== --}}
        <span class="chip-pink">{{ $news->category }}</span>
        <h1 class="mt-3 font-display text-3xl font-bold leading-tight text-bm-ink sm:text-4xl">
            {{ $news->title }}
        </h1>
        <div class="mt-4 flex flex-wrap items-center gap-3 text-sm font-semibold text-slate-400">
            <span class="inline-flex items-center gap-1.5">
                <span class="grid h-7 w-7 place-items-center rounded-full bg-bm-purple/10 text-bm-purple">✍️</span>
                {{ optional($news->author)->name ?? 'Tim MONSTER HUB' }}
            </span>
            <span>·</span>
            <span>📅 {{ optional($news->published_at)->translatedFormat('d M Y') }}</span>
            <span>·</span>
            <span>👁️ {{ number_format($news->views, 0, ',', '.') }} kali dibaca</span>
        </div>

        {{-- ===================== BANNER ===================== --}}
        <div class="mt-6 overflow-hidden rounded-3xl shadow-[var(--shadow-pop)]">
            <x-cover :src="$news->thumbnail" :label="$news->title" emoji="📰"
                     rounded="rounded-3xl" class="aspect-video w-full" />
        </div>

        {{-- ===================== KONTEN ===================== --}}
        <div class="prose-bm mt-8 text-slate-300">
            {!! nl2br(e($news->content)) !!}
        </div>

        {{-- ===================== KOMENTAR ===================== --}}
        @php
            $commentCount = $news->comments->count()
                + $news->comments->sum(fn ($c) => $c->replies->count());
        @endphp
        <section class="mt-12 border-t border-white/10 pt-8">
            <h2 class="mb-4 font-display text-xl font-bold text-bm-ink">
                Komentar 💬 <span class="text-slate-400">({{ number_format($commentCount, 0, ',', '.') }})</span>
            </h2>

            {{-- Form komentar --}}
            @auth
                <form method="POST" action="{{ route('news.comments.store', $news) }}" class="card mb-6">
                    @csrf
                    <label for="content" class="form-label">Tulis komentar</label>
                    <textarea name="content" id="content" rows="3"
                              placeholder="Apa pendapatmu soal berita ini, MONBEBE?" class="form-textarea">{{ old('content') }}</textarea>
                    @error('content')<p class="form-error">{{ $message }}</p>@enderror
                    <div class="mt-3 text-right">
                        <button type="submit" class="btn-primary">Kirim Komentar 💜</button>
                    </div>
                </form>
            @else
                <div class="mb-6 rounded-3xl border-2 border-dashed border-bm-purple/20 bg-bm-purple/5 p-5 text-center">
                    <p class="text-sm text-slate-300">Mau ikut ngobrol? <a href="{{ route('login') }}" class="font-semibold text-bm-purple hover:underline">Masuk dulu yuk!</a> 💜</p>
                </div>
            @endauth

            {{-- List komentar --}}
            @if ($news->comments->isEmpty())
                <x-empty-state emoji="💬" title="Belum ada komentar"
                               message="Jadi yang pertama berkomentar di berita ini!" />
            @else
                <div class="space-y-5">
                    @foreach ($news->comments as $comment)
                        <div class="flex gap-3" x-data="{ replying: false }">
                            <div class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-bm-pink/10 font-display font-bold text-bm-pink">
                                {{ mb_strtoupper(mb_substr($comment->user->name ?? '?', 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <span class="font-display font-bold text-bm-ink">{{ $comment->user->name ?? 'MONBEBE' }}</span>
                                        <span class="text-xs font-semibold text-slate-400">{{ optional($comment->created_at)->translatedFormat('d M Y') }}</span>
                                    </div>
                                    <div class="mt-1 text-sm text-slate-300">{!! nl2br(e($comment->content)) !!}</div>
                                </div>

                                @auth
                                    <div class="mt-1.5">
                                        <button type="button" @click="replying = !replying"
                                                class="rounded-full bg-white/10 px-3 py-1.5 text-sm font-semibold text-slate-400 transition hover:bg-bm-purple/10 hover:text-bm-purple">
                                            ↩️ Balas
                                        </button>
                                    </div>
                                    <form x-show="replying" x-cloak x-transition method="POST"
                                          action="{{ route('news.comments.store', $news) }}" class="mt-2">
                                        @csrf
                                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                        <textarea name="content" rows="2" class="form-textarea"
                                                  placeholder="Balas {{ $comment->user->name ?? 'komentar ini' }}..." required></textarea>
                                        <div class="mt-2 flex justify-end gap-2">
                                            <button type="button" @click="replying = false" class="btn-ghost btn-sm">Batal</button>
                                            <button type="submit" class="btn-primary btn-sm">Kirim Balasan 💬</button>
                                        </div>
                                    </form>
                                @endauth

                                {{-- Balasan --}}
                                @if ($comment->replies->isNotEmpty())
                                    <div class="mt-3 space-y-4 border-l-2 border-white/10 pl-4 sm:ml-8">
                                        @foreach ($comment->replies as $reply)
                                            <div class="flex gap-3">
                                                <div class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-bm-purple/10 font-display text-sm font-bold text-bm-purple">
                                                    {{ mb_strtoupper(mb_substr($reply->user->name ?? '?', 0, 1)) }}
                                                </div>
                                                <div class="min-w-0 flex-1 rounded-2xl border border-white/10 bg-white/5 p-3">
                                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                                        <span class="font-display text-sm font-bold text-bm-ink">{{ $reply->user->name ?? 'MONBEBE' }}</span>
                                                        <span class="text-xs font-semibold text-slate-400">{{ optional($reply->created_at)->translatedFormat('d M Y') }}</span>
                                                    </div>
                                                    <div class="mt-1 text-sm text-slate-300">{!! nl2br(e($reply->content)) !!}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </article>
</x-layouts.app>
