<x-layouts.app title="Berita">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        {{-- ===================== HEADER ===================== --}}
        <div class="mb-8 text-center sm:text-left">
            <span class="section-kicker">Update Terbaru</span>
            <h1 class="section-title">Kabar BABYMONSTER 📰</h1>
            <p class="mt-2 max-w-xl text-slate-400">
                Comeback, rilisan baru, sampai keseruan di balik layar. Semua berita terhangat buat MONBEBE! ✨
            </p>
        </div>

        {{-- ===================== FILTER + SEARCH ===================== --}}
        @php
            $categories = ['Comeback', 'Rilis', 'Event', 'Umum'];
            $activeCat = request('category');
        @endphp
        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            {{-- Kategori --}}
            <div class="no-scrollbar flex gap-1 overflow-x-auto rounded-full border-2 border-white/10 bg-white/5 p-1">
                <a href="{{ route('news.index', ['q' => request('q')]) }}"
                   @class([
                       'shrink-0 rounded-full px-4 py-1.5 text-sm font-semibold transition',
                       'bg-bm-purple text-white shadow-[var(--shadow-pop)]' => ! $activeCat,
                       'text-slate-400 hover:text-bm-purple' => $activeCat,
                   ])>✨ Semua</a>
                @foreach ($categories as $cat)
                    <a href="{{ route('news.index', ['category' => $cat, 'q' => request('q')]) }}"
                       @class([
                           'shrink-0 rounded-full px-4 py-1.5 text-sm font-semibold transition',
                           'bg-bm-purple text-white shadow-[var(--shadow-pop)]' => $activeCat === $cat,
                           'text-slate-400 hover:text-bm-purple' => $activeCat !== $cat,
                       ])>{{ $cat }}</a>
                @endforeach
            </div>

            {{-- Pencarian --}}
            <form method="GET" action="{{ route('news.index') }}" class="flex gap-2">
                @if ($activeCat)
                    <input type="hidden" name="category" value="{{ $activeCat }}">
                @endif
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="🔍 Cari berita..." class="form-input sm:w-64">
                <button type="submit" class="btn-primary shrink-0">Cari</button>
            </form>
        </div>

        {{-- ===================== GRID ===================== --}}
        @if ($news->isEmpty())
            <x-empty-state emoji="📰" title="Belum ada berita"
                           message="Belum ada kabar di kategori ini. Coba pilih kategori lain atau kembali lagi nanti, ya!">
                <x-slot:action>
                    <a href="{{ route('news.index') }}" class="btn-primary">Lihat Semua Berita →</a>
                </x-slot:action>
            </x-empty-state>
        @else
            <div class="grid gap-6 md:grid-cols-3">
                @foreach ($news as $item)
                    <x-news-card :news="$item" />
                @endforeach
            </div>

            <div class="mt-8">
                {{ $news->withQueryString()->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
