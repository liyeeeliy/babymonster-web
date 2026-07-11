@props(['news'])

<a href="{{ route('news.show', $news) }}" class="card card-hover group block overflow-hidden !p-0">
    <div class="relative aspect-video overflow-hidden">
        <x-cover :src="$news->thumbnail" :label="$news->title" emoji="📰" rounded="rounded-none"
                 class="h-full w-full transition duration-500 group-hover:scale-105" />

        {{-- Gradien gelap di atas foto: menjaga chip kategori tetap terbaca
             walau fotonya terang di area pojok kiri-atas. --}}
        <div class="pointer-events-none absolute inset-x-0 top-0 h-24 bg-gradient-to-b from-black/60 to-transparent"></div>

        <span class="absolute left-3 top-3 chip-pink">{{ $news->category }}</span>
    </div>

    <div class="p-5">
        <p class="text-xs font-semibold text-slate-400">
            {{ optional($news->published_at)->translatedFormat('d M Y') }}
            · 👁️ {{ $news->views }}
            · ⏱️ {{ $news->reading_time }} mnt baca
        </p>

        <h3 class="mt-1 line-clamp-2 font-display text-lg font-bold text-bm-ink group-hover:text-bm-purple">
            {{ $news->title }}
        </h3>

        <p class="mt-2 line-clamp-2 text-sm text-slate-400">{{ $news->excerpt }}</p>

        <div class="mt-4 flex items-center justify-between gap-3 border-t border-white/10 pt-3">
            <span class="truncate text-xs text-slate-500">oleh {{ $news->author?->name ?? 'MONSTER HUB' }}</span>
            <span class="shrink-0 text-sm font-semibold text-bm-purple">Baca selengkapnya →</span>
        </div>
    </div>
</a>
