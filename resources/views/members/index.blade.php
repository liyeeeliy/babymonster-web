<x-layouts.app title="Member">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        {{-- ===================== HEADER ===================== --}}
        <div class="mb-10 text-center">
            <span class="section-kicker">Meet The Members</span>
            <h1 class="section-title">Member BABYMONSTER 🎤</h1>
            <p class="mx-auto mt-2 max-w-xl text-slate-400">
                Kenalan lebih dekat sama para monster kesayangan kita. Klik buat lihat profil lengkapnya! 💜
            </p>
        </div>

        {{-- ===================== GRID ===================== --}}
        @if ($members->isEmpty())
            <x-empty-state emoji="🎤" title="Belum ada member"
                           message="Data member belum tersedia. Nantikan update-nya, ya!" />
        @else
            <div class="grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($members as $m)
                    <div class="flex justify-center">
                        <x-member-card :member="$m" />
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>
