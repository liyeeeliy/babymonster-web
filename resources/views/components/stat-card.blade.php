@props(['label' => '', 'value' => '', 'emoji' => '✨', 'tone' => 'purple'])

@php
    $tones = [
        'purple' => 'text-violet-300',
        'pink' => 'text-fuchsia-300',
        'mint' => 'text-emerald-300',
        'yellow' => 'text-amber-300',
    ];
    $accent = $tones[$tone] ?? $tones['purple'];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-3xl border border-white/10 bg-white/[0.04] p-5 shadow-[var(--shadow-soft)] backdrop-blur-xl']) }}>
    <div class="mb-2 text-2xl">{{ $emoji }}</div>
    <div class="font-display text-2xl font-bold text-bm-ink sm:text-3xl">{{ $value }}</div>
    <div class="mt-0.5 text-xs font-semibold uppercase tracking-wide {{ $accent }}">{{ $label }}</div>
</div>
