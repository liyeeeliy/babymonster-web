<x-layouts.app title="Voting">
    <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        {{-- ===================== HEADER ===================== --}}
        <div class="mb-8 text-center">
            <span class="section-kicker">Suaramu Berarti</span>
            <h1 class="section-title">Voting MONBEBE 🗳️</h1>
            <p class="mx-auto mt-2 max-w-xl text-slate-400">
                Dukung member favoritmu! Setiap fans dapat <b>1 suara</b> per voting. Yuk ramaikan! ✨
            </p>
        </div>

        {{-- ===================== LIST POLL ===================== --}}
        @if ($polls->isEmpty())
            <x-empty-state emoji="🗳️" title="Belum ada voting aktif"
                           message="Nantikan sesi voting berikutnya, ya MONBEBE! Pantau terus MONSTER HUB. 💜" />
        @else
            <div class="space-y-8">
                @foreach ($polls as $poll)
                    @php
                        $total = $poll->options->sum('vote_count');
                        $canVote = ! $poll->has_voted && $poll->isOpen();
                    @endphp

                    <div class="card">
                        {{-- Header kartu --}}
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h2 class="font-display text-xl font-bold text-bm-ink sm:text-2xl">{{ $poll->title }}</h2>
                                <p class="mt-1 text-sm text-slate-400">
                                    @if ($poll->isOpen())
                                        @if ($poll->ends_at)
                                            🕒 Ditutup {{ optional($poll->ends_at)->translatedFormat('d M Y, H:i') }}
                                        @else
                                            🕒 Voting sedang dibuka
                                        @endif
                                    @else
                                        🔒 Voting telah ditutup
                                    @endif
                                </p>
                            </div>
                            @if ($poll->isOpen())
                                <span class="chip-mint shrink-0">🟢 Aktif</span>
                            @else
                                <span class="chip-slate shrink-0">Selesai</span>
                            @endif
                        </div>

                        @if ($canVote)
                            {{-- ===================== FORM VOTE ===================== --}}
                            <form method="POST" action="{{ route('voting.vote', $poll) }}"
                                  x-data="{ selected: null }" class="mt-5">
                                @csrf
                                <div class="grid gap-3 sm:grid-cols-2">
                                    @foreach ($poll->options as $opt)
                                        <label class="flex cursor-pointer items-center gap-3 rounded-2xl border-2 p-3 transition"
                                               :class="selected === {{ $opt->id }}
                                                    ? 'border-bm-purple bg-bm-purple/5 shadow-[var(--shadow-soft)]'
                                                    : 'border-white/10 hover:border-bm-purple/40'">
                                            <input type="radio" name="option_id" value="{{ $opt->id }}"
                                                   x-model.number="selected" required class="sr-only">
                                            <x-cover :src="$opt->image" :label="$opt->label" emoji="🎤"
                                                     rounded="rounded-xl" class="h-12 w-12 shrink-0" />
                                            <span class="min-w-0 flex-1 font-display font-bold text-bm-ink">{{ $opt->label }}</span>
                                            <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full border-2 text-xs font-bold transition"
                                                  :class="selected === {{ $opt->id }}
                                                        ? 'border-bm-purple bg-bm-purple text-white'
                                                        : 'border-slate-300 text-transparent'">✓</span>
                                        </label>
                                    @endforeach
                                </div>

                                @error('option_id')<p class="form-error mt-2">{{ $message }}</p>@enderror

                                <div class="mt-5 flex flex-wrap items-center gap-3">
                                    <button type="submit" :disabled="!selected"
                                            class="btn-primary btn-lg"
                                            :class="!selected && 'cursor-not-allowed opacity-50'">
                                        Kirim Suara 🗳️
                                    </button>
                                    <a href="{{ route('voting.results', $poll) }}"
                                       class="text-sm font-semibold text-bm-purple hover:underline">Lihat hasil lengkap →</a>
                                </div>
                            </form>
                        @else
                            {{-- ===================== HASIL (BARS) ===================== --}}
                            <div class="mt-5 flex flex-wrap items-center gap-2">
                                @if ($poll->has_voted)
                                    <span class="chip-mint">✓ Kamu sudah vote</span>
                                @else
                                    <span class="chip-slate">🔒 Ditutup</span>
                                @endif
                                <span class="text-xs font-semibold text-slate-400">Total {{ number_format($total, 0, ',', '.') }} suara</span>
                            </div>

                            <div class="mt-4 space-y-3">
                                @foreach ($poll->options as $opt)
                                    @php $pct = round($opt->vote_count / max(1, $total) * 100); @endphp
                                    <div>
                                        <div class="mb-1 flex items-center justify-between text-sm font-semibold text-bm-ink">
                                            <span class="min-w-0 truncate">{{ $opt->label }}</span>
                                            <span class="shrink-0 pl-3 text-slate-400">{{ number_format($opt->vote_count, 0, ',', '.') }} · {{ $pct }}%</span>
                                        </div>
                                        <div class="h-3 overflow-hidden rounded-full bg-bm-purple/10">
                                            <div class="h-full rounded-full bg-bm-purple transition-all" style="width: {{ $pct }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-5">
                                <a href="{{ route('voting.results', $poll) }}"
                                   class="text-sm font-semibold text-bm-purple hover:underline">Lihat hasil lengkap →</a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>
