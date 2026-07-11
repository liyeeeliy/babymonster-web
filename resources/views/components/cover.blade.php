@props(['src' => null, 'label' => '', 'emoji' => '💿', 'rounded' => 'rounded-3xl'])

@php
    $initials = collect(explode(' ', trim((string) $label)))
        ->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');

    // Palet gradien (string literal agar terdeteksi Tailwind), dipilih dari hash label.
    $grads = [
        'from-bm-purple to-bm-pink',
        'from-bm-pink to-bm-yellow',
        'from-bm-violet to-bm-cyan',
        'from-bm-cyan to-bm-mint',
        'from-bm-purple to-bm-violet',
    ];
    $g = $grads[abs(crc32((string) $label)) % count($grads)];

    $url = $src ? (\Illuminate\Support\Str::startsWith($src, ['http', '/']) ? $src : asset('storage/' . $src)) : null;
@endphp

@if ($url)
    <img src="{{ $url }}" alt="{{ $label }}" {{ $attributes->merge(['class' => "object-cover $rounded"]) }}>
@else
    <div {{ $attributes->merge(['class' => "bg-gradient-to-br $g $rounded flex flex-col items-center justify-center gap-1 text-white"]) }}>
        <span class="text-4xl drop-shadow-md">{{ $emoji }}</span>
        <span class="font-display text-lg font-bold tracking-wide drop-shadow">{{ $initials ?: '★' }}</span>
    </div>
@endif
