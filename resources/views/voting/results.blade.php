<x-layouts.app :title="'Hasil · ' . $votePoll->title">
    <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        {{-- ===================== BACK LINK ===================== --}}
        <a href="{{ route('voting.index') }}"
           class="mb-6 inline-flex items-center gap-2 text-sm font-semibold text-slate-400 hover:text-bm-purple">
            ← Kembali ke Voting
        </a>

        {{-- ===================== HEADER ===================== --}}
        @php
            $totalVotes = collect($results)->sum('vote_count');
            $medals = ['🥇', '🥈', '🥉'];
        @endphp

        <div class="mb-8 text-center">
            <span class="section-kicker">Hasil Voting</span>
            <h1 class="section-title">{{ $votePoll->title }} 🏆</h1>
            <p class="mt-2 text-slate-400">
                Total <b>{{ number_format($totalVotes, 0, ',', '.') }}</b> suara terkumpul dari MONBEBE. 💜
            </p>
        </div>

        {{-- ===================== RANKED BARS ===================== --}}
        @if (empty($results))
            <x-empty-state emoji="🗳️" title="Belum ada suara"
                           message="Voting ini belum menerima suara apa pun." />
        @else
            <div class="card space-y-5">
                @foreach ($results as $i => $r)
                    <div>
                        <div class="mb-1.5 flex items-center justify-between gap-3">
                            <span class="flex min-w-0 items-center gap-2 font-display font-bold text-bm-ink">
                                <span class="text-lg">{{ $medals[$i] ?? '#' . ($i + 1) }}</span>
                                <span class="truncate">{{ $r['label'] }}</span>
                            </span>
                            <span class="shrink-0 text-sm font-semibold text-slate-400">
                                {{ number_format($r['vote_count'], 0, ',', '.') }} suara · {{ round($r['percentage']) }}%
                            </span>
                        </div>
                        <div class="h-4 overflow-hidden rounded-full bg-bm-purple/10">
                            <div class="h-full rounded-full transition-all {{ $i === 0 ? 'bg-gradient-to-r from-bm-purple to-bm-pink' : 'bg-bm-purple/70' }}"
                                 style="width: {{ max(2, round($r['percentage'])) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="mt-8 text-center">
            <a href="{{ route('voting.index') }}" class="btn-outline">← Lihat Semua Voting</a>
        </div>
    </div>
</x-layouts.app>
