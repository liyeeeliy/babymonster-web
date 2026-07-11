<x-layouts.app :title="$forumPost->title">
    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
        {{-- ===================== BACK LINK ===================== --}}
        <a href="{{ route('forum.index') }}"
           class="mb-6 inline-flex items-center gap-2 text-sm font-semibold text-slate-400 hover:text-bm-purple">
            ← Kembali ke Forum
        </a>

        @php
            $commentCount = $forumPost->comments->count()
                + $forumPost->comments->sum(fn ($c) => $c->replies->count());
        @endphp

        {{-- ===================== POST CARD ===================== --}}
        <article class="card">
            <div class="flex items-center gap-3">
                <div class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-bm-purple/10 font-display font-bold text-bm-purple">
                    {{ mb_strtoupper(mb_substr($forumPost->user->name ?? '?', 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="font-display font-bold text-bm-ink">{{ $forumPost->user->name ?? 'MONBEBE' }}</p>
                    <p class="text-xs font-semibold text-slate-400">
                        {{ optional($forumPost->created_at)->translatedFormat('d M Y, H:i') }}
                    </p>
                </div>
            </div>

            <h1 class="mt-5 font-display text-2xl font-bold leading-tight text-bm-ink sm:text-3xl">
                {{ $forumPost->title }}
            </h1>

            @if ($forumPost->photo)
                <div class="mt-5 overflow-hidden rounded-3xl">
                    <x-cover :src="$forumPost->photo" :label="$forumPost->title" emoji="📷"
                             rounded="rounded-3xl" class="max-h-[32rem] w-full" />
                </div>
            @endif

            <div class="prose-bm mt-5 text-slate-300">
                {!! nl2br(e($forumPost->content)) !!}
            </div>

            {{-- Action row --}}
            <div class="mt-6 flex flex-wrap items-center gap-3 border-t border-white/10 pt-5">
                <x-like-button :likeable="$forumPost" type="forum_post" />

                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1.5 text-sm font-semibold text-slate-400">
                    🔁 {{ number_format($forumPost->share_count ?? 0, 0, ',', '.') }}
                </span>

                @if (auth()->id() === $forumPost->user_id || auth()->user()?->isAdmin())
                    <form method="POST" action="{{ route('forum.destroy', $forumPost) }}"
                          onsubmit="return confirm('Yakin mau hapus post ini?')" class="ml-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-3 py-1.5 text-sm font-semibold text-rose-500 transition hover:bg-rose-100">
                            🗑️ Hapus
                        </button>
                    </form>
                @endif
            </div>
        </article>

        {{-- ===================== KOMENTAR ===================== --}}
        <section class="mt-8">
            <h2 class="mb-4 font-display text-xl font-bold text-bm-ink">
                Komentar 💬 <span class="text-slate-400">({{ number_format($commentCount, 0, ',', '.') }})</span>
            </h2>

            {{-- Form komentar --}}
            @auth
                <form method="POST" action="{{ route('forum.comments.store', $forumPost) }}" class="card mb-6">
                    @csrf
                    <label for="content" class="form-label">Tulis komentar</label>
                    <textarea name="content" id="content" rows="3"
                              placeholder="Bagikan pendapatmu, MONBEBE..." class="form-textarea">{{ old('content') }}</textarea>
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
            @if ($forumPost->comments->isEmpty())
                <x-empty-state emoji="💬" title="Belum ada komentar"
                               message="Jadi yang pertama berkomentar di postingan ini!" />
            @else
                <div class="space-y-5">
                    @foreach ($forumPost->comments as $comment)
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
                                <div class="mt-1.5 flex items-center gap-2">
                                    <x-like-button :likeable="$comment" type="comment" :count="$comment->likes->count()" />
                                    @auth
                                        <button type="button" @click="replying = !replying"
                                                class="rounded-full bg-white/10 px-3 py-1.5 text-sm font-semibold text-slate-400 transition hover:bg-bm-purple/10 hover:text-bm-purple">
                                            ↩️ Balas
                                        </button>
                                    @endauth
                                </div>

                                @auth
                                    <form x-show="replying" x-cloak x-transition method="POST"
                                          action="{{ route('forum.comments.store', $forumPost) }}" class="mt-2">
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
                                                <div class="min-w-0 flex-1">
                                                    <div class="rounded-2xl border border-white/10 bg-white/5 p-3">
                                                        <div class="flex flex-wrap items-center justify-between gap-2">
                                                            <span class="font-display text-sm font-bold text-bm-ink">{{ $reply->user->name ?? 'MONBEBE' }}</span>
                                                            <span class="text-xs font-semibold text-slate-400">{{ optional($reply->created_at)->translatedFormat('d M Y') }}</span>
                                                        </div>
                                                        <div class="mt-1 text-sm text-slate-300">{!! nl2br(e($reply->content)) !!}</div>
                                                    </div>
                                                    <div class="mt-1.5">
                                                        <x-like-button :likeable="$reply" type="comment" :count="$reply->likes->count()" />
                                                    </div>
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
    </div>
</x-layouts.app>
