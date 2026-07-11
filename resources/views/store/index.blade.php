<x-layouts.app title="Toko Album">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        {{-- ===================== HEADER ===================== --}}
        <div class="mb-8 text-center sm:text-left">
            <span class="section-kicker">Toko Resmi</span>
            <h1 class="section-title">Koleksi Album 💿</h1>
            <p class="mt-2 max-w-xl text-slate-400">
                Lengkapi koleksimu, MONBEBE! Semua album resmi BABYMONSTER ada di sini. ✨
            </p>
        </div>

        {{-- ===================== FILTER BAR ===================== --}}
        <form method="GET" action="{{ route('store.index') }}"
              class="card mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-12 lg:items-end">
            <div class="lg:col-span-5">
                <label for="q" class="form-label">🔍 Cari Album</label>
                <input type="text" name="q" id="q" value="{{ request('q') }}"
                       placeholder="Cari album..." class="form-input">
            </div>
            <div class="lg:col-span-2">
                <label for="min_price" class="form-label">Harga Min</label>
                <input type="number" name="min_price" id="min_price" min="0" step="1000"
                       value="{{ request('min_price') }}" placeholder="0" class="form-input">
            </div>
            <div class="lg:col-span-2">
                <label for="max_price" class="form-label">Harga Max</label>
                <input type="number" name="max_price" id="max_price" min="0" step="1000"
                       value="{{ request('max_price') }}" placeholder="∞" class="form-input">
            </div>
            <div class="flex gap-2 sm:col-span-2 lg:col-span-3">
                <button type="submit" class="btn-primary flex-1">Terapkan</button>
                <a href="{{ route('store.index') }}" class="btn-ghost">Reset</a>
            </div>
        </form>

        {{-- ===================== GRID ===================== --}}
        @if ($albums->isEmpty())
            <x-empty-state emoji="💿" title="Album tidak ditemukan"
                           message="Coba ubah kata kunci atau atur ulang filter pencarianmu, ya!">
                <x-slot:action>
                    <a href="{{ route('store.index') }}" class="btn-primary">Lihat Semua Album →</a>
                </x-slot:action>
            </x-empty-state>
        @else
            <p class="mb-4 text-sm font-semibold text-slate-400">
                Menampilkan {{ $albums->total() }} album 💜
            </p>
            <div class="grid grid-cols-2 gap-5 md:grid-cols-3 lg:grid-cols-4">
                @foreach ($albums as $album)
                    <x-album-card :album="$album" />
                @endforeach
            </div>

            <div class="mt-8">
                {{ $albums->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
