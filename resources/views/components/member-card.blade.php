@props(['member'])

<a href="{{ route('members.show', $member) }}" class="group block w-40 shrink-0 sm:w-full">
    <div class="relative aspect-[3/4] overflow-hidden rounded-3xl shadow-[var(--shadow-soft)]">
        {{-- Foto: zoom in saat hover --}}
        {{-- object-position 50% 20%: sedikit di bawah "rata atas", biar tidak terlalu naik --}}
        <x-cover :src="$member->photo" :label="$member->name" emoji="🎤" rounded="rounded-none"
                 class="h-full w-full object-[50%_20%] transition duration-500 group-hover:scale-110" />

        {{-- Overlay gelap + info member.
             Layar kecil tidak punya hover, jadi overlay & teks selalu tampak.
             Mulai breakpoint sm, overlay disembunyikan dan muncul saat hover. --}}
        <div class="absolute inset-0 flex flex-col justify-end bg-gradient-to-t from-black/85 to-black/15 p-4
                    opacity-100 transition-opacity duration-500
                    sm:opacity-0 sm:group-hover:opacity-100">
            {{-- Teks naik ke atas saat hover --}}
            <div class="transition-transform duration-500 sm:translate-y-6 sm:group-hover:translate-y-0">
                <p class="font-display text-base font-bold tracking-wide text-white sm:text-lg">{{ $member->name }}</p>
                <p class="mt-1 line-clamp-3 text-xs text-bm-pink-soft">{{ $member->position }}</p>
            </div>
        </div>
    </div>
</a>
