@props(['likeable', 'type', 'count' => null])

@php
    $count = is_null($count) ? $likeable->likes()->count() : $count;
    $liked = auth()->check() && $likeable->likes()->where('user_id', auth()->id())->exists();
@endphp

@auth
    <form method="POST" action="{{ route('likes.toggle') }}" class="inline">
        @csrf
        <input type="hidden" name="type" value="{{ $type }}">
        <input type="hidden" name="id" value="{{ $likeable->id }}">
        <button type="submit"
                @class([
                    'inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-sm font-semibold transition',
                    'bg-bm-pink/15 text-fuchsia-200' => $liked,
                    'bg-white/5 text-slate-400 hover:bg-bm-pink/10 hover:text-fuchsia-200' => ! $liked,
                ])>
            <span>{{ $liked ? '💜' : '🤍' }}</span>
            <span>{{ $count }}</span>
        </button>
    </form>
@else
    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 rounded-full bg-white/5 px-3 py-1.5 text-sm font-semibold text-slate-400 hover:bg-bm-pink/10 hover:text-fuchsia-200">
        <span>🤍</span> <span>{{ $count }}</span>
    </a>
@endauth
