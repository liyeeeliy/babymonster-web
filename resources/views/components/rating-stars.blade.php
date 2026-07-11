@props(['rating' => 0, 'count' => null, 'size' => 'text-sm'])

@php $rating = (float) $rating; @endphp

<div {{ $attributes->merge(['class' => "inline-flex items-center gap-1 $size"]) }}>
    <span class="text-bm-yellow">
        @for ($i = 1; $i <= 5; $i++)
            @if ($i <= round($rating))★@else<span class="text-slate-300">★</span>@endif
        @endfor
    </span>
    <span class="font-semibold text-slate-300">{{ number_format($rating, 1) }}</span>
    @if (! is_null($count))
        <span class="text-slate-400">({{ $count }})</span>
    @endif
</div>
