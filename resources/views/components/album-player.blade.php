@props(['album'])

@php
    use Illuminate\Support\Str;

    $tracks = is_array($album->tracklist) ? array_values($album->tracklist) : [];
    $rawTrackAudio = is_array($album->track_audio) ? array_values($album->track_audio) : [];

    // Ubah path jadi URL: kalau diawali http/ atau '/' dipakai apa adanya,
    // selain itu diperlakukan sebagai file di storage (hasil upload admin).
    $toUrl = function ($src) {
        $src = trim((string) $src);
        if ($src === '') return null;
        return Str::startsWith($src, ['http', '/']) ? $src : asset('storage/' . $src);
    };

    // Audio per track, index sejajar dengan $tracks (null bila belum ada).
    $audios = [];
    foreach ($tracks as $i => $track) {
        $audios[$i] = $toUrl($rawTrackAudio[$i] ?? null);
    }

    // Fallback: kalau tidak ada audio per-track tapi ada preview_audio lama,
    // pakai preview itu untuk semua track.
    if (empty(array_filter($audios)) && $album->preview_audio) {
        $preview = $toUrl($album->preview_audio);
        $audios = array_fill(0, max(count($tracks), 1), $preview);
    }
@endphp

<div x-data="albumPlayer({{ \Illuminate\Support\Js::from($tracks) }}, {{ \Illuminate\Support\Js::from($audios) }})"
     class="overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-[#241546] to-[#0d0a1c] text-white shadow-[var(--shadow-pop)]">
    <div class="p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-white/60"
                   x-text="hasCurrentAudio ? 'Preview Audio' : 'Pemutar Simulasi'">Pemutar Simulasi</p>
                <h3 class="font-display text-xl font-bold" x-text="currentTitle">—</h3>
            </div>
            <!-- Equalizer -->
            <div class="flex h-8 items-end gap-1" :class="playing && 'eq-playing'">
                <span class="eq-bar h-2"></span>
                <span class="eq-bar h-4"></span>
                <span class="eq-bar h-6"></span>
                <span class="eq-bar h-3"></span>
            </div>
        </div>

        <!-- Progress -->
        <div class="mt-4 h-1.5 overflow-hidden rounded-full bg-white/20">
            <div class="h-full rounded-full bg-bm-yellow transition-all" :style="`width: ${progress}%`"></div>
        </div>

        <!-- Kontrol -->
        <div class="mt-4 flex items-center justify-center gap-4">
            {{-- Prev: ikon SVG putih (ganti emoji biru) --}}
            <button @click="prev()" class="text-white/70 transition hover:text-white" title="Sebelumnya">
                <svg viewBox="0 0 24 24" fill="currentColor" class="h-7 w-7">
                    <path d="M6 6h2v12H6zM20 6v12l-9-6z" />
                </svg>
            </button>
            <button @click="toggle()"
                    class="grid h-14 w-14 place-items-center rounded-full bg-bm-pink text-black shadow-lg transition hover:scale-105">
                {{-- Ikon SVG (fill=currentColor) supaya warnanya ikut text-black (hitam), diperbesar h-8 --}}
                <svg x-show="!playing" viewBox="0 0 24 24" fill="currentColor" class="h-8 w-8 translate-x-0.5">
                    <path d="M8 5v14l11-7z" />
                </svg>
                <svg x-show="playing" x-cloak viewBox="0 0 24 24" fill="currentColor" class="h-8 w-8">
                    <path d="M6 5h4v14H6zM14 5h4v14h-4z" />
                </svg>
            </button>
            {{-- Next: ikon SVG putih (ganti emoji biru) --}}
            <button @click="next()" class="text-white/70 transition hover:text-white" title="Berikutnya">
                <svg viewBox="0 0 24 24" fill="currentColor" class="h-7 w-7">
                    <path d="M16 6h2v12h-2zM4 6l9 6-9 6z" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Tracklist -->
    @if (count($tracks))
        <div class="border-t border-white/10 bg-black/10 p-4">
            <p class="mb-2 px-2 text-xs font-semibold uppercase tracking-wider text-white/50">Tracklist</p>
            <ul class="space-y-1">
                @foreach ($tracks as $i => $track)
                    <li>
                        <button @click="select({{ $i }})"
                                class="flex w-full items-center gap-3 rounded-xl px-3 py-2 text-left text-sm transition hover:bg-white/10"
                                :class="current === {{ $i }} && 'bg-white/15'">
                            <span class="w-5 text-center text-white/50" x-text="current === {{ $i }} && playing ? '♪' : '{{ $i + 1 }}'">{{ $i + 1 }}</span>
                            <span class="flex-1 font-medium">{{ $track }}</span>
                            <span x-show="current === {{ $i }} && playing" x-cloak class="text-bm-yellow">▶</span>
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Satu elemen audio; src diganti per track lewat JS.
         Pakai x-on:error (sengaja bukan sintaks @ Alpine) agar tidak bentrok
         dengan direktif error bawaan Blade. --}}
    <audio x-ref="audio" preload="none"
           x-on:ended="next()"
           x-on:timeupdate="onTimeUpdate()"
           x-on:error="onAudioError()"></audio>
</div>
