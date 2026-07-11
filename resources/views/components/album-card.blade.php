@props(['album'])

<a href="{{ route('store.show', $album) }}" class="card card-hover group block overflow-hidden !p-0">
    <div class="relative aspect-square overflow-hidden">
        <x-cover :src="$album->cover" :label="$album->name" emoji="💿" rounded="rounded-none"
                 class="h-full w-full transition duration-500 group-hover:scale-105" />
        <span class="absolute left-3 top-3 chip-yellow">💿 Album</span>
        @if ($album->stock <= 0)
            <span class="absolute right-3 top-3 chip bg-rose-500 text-white">Habis</span>
        @endif
    </div>
    <div class="p-4">
        <h3 class="line-clamp-1 font-display font-bold text-bm-ink">{{ $album->name }}</h3>
        <div class="mt-1">
            <x-rating-stars :rating="$album->average_rating" size="text-xs" />
        </div>
        <div class="mt-3 flex items-center justify-between">
            <span class="font-display text-lg font-bold text-bm-purple">Rp{{ number_format($album->price, 0, ',', '.') }}</span>
            <span class="chip-slate">Stok {{ $album->stock }}</span>
        </div>
    </div>
</a>
