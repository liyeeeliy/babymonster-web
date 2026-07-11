@props(['emoji' => '🫧', 'title' => 'Belum ada apa-apa di sini', 'message' => null])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center rounded-3xl border-2 border-dashed border-white/10 bg-white/[0.02] px-6 py-16 text-center']) }}>
    <div class="mb-3 text-5xl animate-float">{{ $emoji }}</div>
    <h3 class="font-display text-xl font-bold text-bm-ink">{{ $title }}</h3>
    @if ($message)
        <p class="mt-1 max-w-sm text-sm text-slate-400">{{ $message }}</p>
    @endif
    @if (isset($action))
        <div class="mt-5">{{ $action }}</div>
    @endif
</div>
