<x-layouts.admin title="Log Aktivitas">
    {{-- Header --}}
    <div class="mb-6">
        <span class="section-kicker">Jejak Aktivitas</span>
        <h2 class="font-display text-2xl font-bold text-bm-ink">Log Aktivitas 📝</h2>
        <p class="mt-1 text-sm text-slate-400">Rekam jejak semua aksi yang terjadi di MONSTER HUB.</p>
    </div>

    @if ($logs->isEmpty())
        <x-empty-state emoji="📝" title="Belum ada aktivitas" message="Aktivitas admin dan sistem akan tercatat di sini." />
    @else
        {{-- Timeline --}}
        <div class="card">
            <ol class="relative space-y-6 border-l-2 border-white/10 pl-6">
                @foreach ($logs as $log)
                    <li class="relative">
                        <span class="absolute -left-[1.95rem] top-1 grid h-6 w-6 place-items-center rounded-full bg-bm-purple text-[11px] text-white ring-4 ring-white">
                            {{ $log->user ? strtoupper(substr($log->user->name, 0, 1)) : '⚙️' }}
                        </span>
                        <div class="flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between sm:gap-4">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-semibold text-bm-ink">{{ $log->user?->name ?? 'Sistem' }}</span>
                                    <span class="chip-purple">{{ $log->action }}</span>
                                </div>
                                @if ($log->description)
                                    <p class="mt-1 text-sm text-slate-400">{{ $log->description }}</p>
                                @endif
                            </div>
                            <time class="shrink-0 text-xs font-semibold text-slate-400">
                                {{ optional($log->created_at)->translatedFormat('d M Y H:i') }}
                            </time>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>

        <div class="mt-8">
            {{ $logs->links() }}
        </div>
    @endif
</x-layouts.admin>
