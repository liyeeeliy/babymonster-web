<x-layouts.app :title="$member->name">
    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        {{-- ===================== BACK LINK ===================== --}}
        <a href="{{ route('members.index') }}"
           class="mb-6 inline-flex items-center gap-2 text-sm font-semibold text-slate-400 hover:text-bm-purple">
            ← Kembali ke Member
        </a>

        <div class="grid gap-8 lg:grid-cols-5 lg:gap-12">
            {{-- ===================== LEFT: PORTRAIT ===================== --}}
            <div class="lg:col-span-2">
                <div class="overflow-hidden rounded-3xl shadow-[var(--shadow-pop)]">
                    <x-cover :src="$member->photo" :label="$member->name" emoji="🎤"
                             rounded="rounded-3xl" class="aspect-[3/4] w-full" />
                </div>
            </div>

            {{-- ===================== RIGHT: INFO ===================== --}}
            <div class="lg:col-span-3">
                <span class="chip-purple">🎤 {{ $member->position }}</span>
                <h1 class="mt-3 font-display text-3xl font-bold leading-tight text-bm-ink sm:text-4xl">
                    {{ $member->name }}
                </h1>

                {{-- Meta --}}
                <div class="mt-4 flex flex-wrap items-center gap-2 text-sm">
                    @if (!empty($member->nationality))
                        <span class="chip-slate">🌏 {{ $member->nationality }}</span>
                    @endif
                    @if (!is_null($member->birth_date))
                        <span class="chip-slate">🎂 {{ optional($member->birth_date)->translatedFormat('d M Y') }}</span>
                    @endif
                    <span class="chip-pink">💜 {{ number_format($member->fans->count(), 0, ',', '.') }} fans</span>
                </div>

                {{-- Deskripsi --}}
                @if (!empty($member->description))
                    <div class="prose-bm mt-6 text-slate-300">
                        {!! nl2br(e($member->description)) !!}
                    </div>
                @endif

                {{-- Biodata Pribadi: tiap label satu baris, nama label dicetak tebal --}}
                @if (!empty($member->biodata) && is_array($member->biodata))
                    <div class="mt-8">
                        <h2 class="mb-3 font-display text-lg font-bold text-bm-ink">Biodata Pribadi 📋</h2>
                        <dl class="overflow-hidden rounded-2xl border border-white/10 bg-white/5 divide-y divide-white/10">
                            @foreach ($member->biodata as $label => $value)
                                <div class="flex flex-col gap-0.5 px-4 py-2.5 sm:flex-row sm:items-baseline sm:gap-4">
                                    <dt class="font-semibold text-bm-ink sm:w-44 sm:shrink-0">{{ $label }}</dt>
                                    <dd class="text-sm text-slate-300 sm:flex-1">{{ $value }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>
                @endif

                {{-- Fans --}}
                <div class="mt-8 rounded-3xl bg-gradient-to-br from-bm-purple/10 to-bm-pink/10 p-5 text-center sm:text-left">
                    <p class="font-display text-lg font-bold text-bm-ink">
                        {{ number_format($member->fans->count(), 0, ',', '.') }} fans menjadikannya bias 💜
                    </p>
                    <p class="mt-1 text-sm text-slate-400">
                        Jadikan {{ $member->name }} bias-mu lewat menu Profil, MONBEBE!
                    </p>
                </div>
            </div>
        </div>

        {{-- ===================== GALERI FOTO (3 GRID) ===================== --}}
        @php
            // Selalu 3 slot: dipotong maks 3, lalu dipadkan null bila kurang.
            $gallery = is_array($member->gallery) ? array_values($member->gallery) : [];
            $gallery = array_pad(array_slice($gallery, 0, 3), 3, null);

            // Tiap item boleh berupa:
            //   'path/foto.jpg'                                  -> posisi crop default (tengah)
            //   ['src' => 'path/foto.jpg', 'pos' => '50% 20%']   -> posisi crop custom (object-position)
            $gallery = array_map(function ($item) {
                if (is_array($item)) {
                    return ['src' => $item['src'] ?? null, 'pos' => $item['pos'] ?? '50% 50%'];
                }
                return ['src' => $item, 'pos' => '50% 50%'];
            }, $gallery);
        @endphp

        <section class="mt-16">
            <div class="mb-6">
                <span class="section-kicker">Galeri</span>
                <h2 class="section-title">Momen {{ $member->name }} 📸</h2>
            </div>

            <div class="grid gap-5 sm:grid-cols-3">
                @foreach ($gallery as $photo)
                    <figure class="group relative aspect-[4/3] overflow-hidden rounded-3xl border border-white/10
                                   shadow-[var(--shadow-soft)] transition-colors duration-500 hover:border-bm-pink/40">
                        {{-- object-position dipakai inline karena nilainya dinamis dari database
                             (Tailwind tidak bisa meng-generate class dinamis saat build). --}}
                        <x-cover :src="$photo['src']" :label="$member->name" emoji="📸" rounded="rounded-none"
                                 class="h-full w-full transition duration-500 group-hover:scale-105"
                                 style="object-position: {{ $photo['pos'] }}" />

                        {{-- Gradien halus saat hover --}}
                        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/60 to-transparent
                                    opacity-0 transition-opacity duration-500 group-hover:opacity-100"></div>
                    </figure>
                @endforeach
            </div>
        </section>
    </div>
</x-layouts.app>
