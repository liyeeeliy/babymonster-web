<x-layouts.admin title="Dashboard">
    {{-- ===================== SAPAAN ===================== --}}
    <div class="mb-6">
        <span class="section-kicker">Halo, Admin 👋</span>
        <h2 class="font-display text-2xl font-bold text-bm-ink">Ringkasan MONSTER HUB ✨</h2>
        <p class="mt-1 text-sm text-slate-400">Pantau penjualan, konten, dan aktivitas MONBEBE dari satu tempat.</p>
    </div>

    {{-- ===================== STAT CARDS ===================== --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-3">
        <x-stat-card emoji="💰" value="Rp{{ number_format($stats['total_sales'], 0, ',', '.') }}" label="Total Penjualan" tone="pink" />
        <x-stat-card emoji="🛍️" :value="number_format($stats['total_orders'])" label="Total Pesanan" tone="purple" />
        <x-stat-card emoji="🧑‍🤝‍🧑" :value="number_format($stats['total_users'])" label="Total User" tone="mint" />
        <x-stat-card emoji="💿" :value="number_format($stats['total_albums'])" label="Album" tone="yellow" />
        <x-stat-card emoji="📰" :value="number_format($stats['total_news'])" label="Berita" tone="purple" />
        <x-stat-card emoji="🎤" :value="number_format($stats['total_members'])" label="Member" tone="pink" />
    </div>

    {{-- ===================== GRAFIK + TERLARIS ===================== --}}
    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        {{-- Grafik Penjualan 12 Bulan --}}
        <div class="card lg:col-span-2">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h3 class="font-display text-lg font-bold text-bm-ink">Penjualan 12 Bulan 📈</h3>
                    <p class="text-xs text-slate-400">Total nominal pesanan yang sudah dibayar</p>
                </div>
                <span class="chip-purple hidden sm:inline-flex">👥 {{ number_format($activeUsers) }} user aktif</span>
            </div>

            @php $max = (int) $monthlySales->max('total'); @endphp

            @if ($max <= 0)
                <x-empty-state emoji="📉" title="Belum ada penjualan" message="Grafik akan muncul setelah ada pesanan yang dibayar." />
            @else
                <div class="flex items-end gap-1.5 sm:gap-2" style="height: 220px">
                    @foreach ($monthlySales as $point)
                        @php
                            $pct = $max > 0 ? round($point->total / $max * 100) : 0;
                            $short = \Illuminate\Support\Carbon::parse($point->month . '-01')->translatedFormat('M');
                        @endphp
                        <div class="group flex h-full flex-1 flex-col items-center justify-end gap-2">
                            <span class="rounded-lg bg-slate-900 px-1.5 py-0.5 text-[10px] font-semibold text-white opacity-0 transition group-hover:opacity-100">
                                Rp{{ number_format($point->total, 0, ',', '.') }}
                            </span>
                            <div class="w-full rounded-t-xl bg-gradient-to-t from-bm-purple to-bm-pink transition-all duration-300 hover:from-bm-pink hover:to-bm-yellow"
                                 style="height: {{ max($pct, 2) }}%"
                                 title="{{ $point->month }} · Rp{{ number_format($point->total, 0, ',', '.') }}"></div>
                            <span class="text-[10px] font-semibold uppercase text-slate-400">{{ $short }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Produk Terlaris --}}
        <div class="card">
            <h3 class="mb-4 font-display text-lg font-bold text-bm-ink">Produk Terlaris 🔥</h3>
            @if ($bestSellers->isEmpty())
                <x-empty-state emoji="📦" title="Belum ada penjualan" message="Produk terlaris muncul di sini." />
            @else
                <ol class="space-y-3">
                    @foreach ($bestSellers as $i => $seller)
                        <li class="flex items-center gap-3">
                            <span class="grid h-8 w-8 shrink-0 place-items-center rounded-2xl bg-bm-purple/10 font-display text-sm font-bold text-bm-purple">
                                {{ $i + 1 }}
                            </span>
                            <span class="min-w-0 flex-1 truncate text-sm font-semibold text-bm-ink">{{ $seller->name }}</span>
                            <span class="chip-yellow shrink-0">{{ number_format($seller->total_qty) }} terjual</span>
                        </li>
                    @endforeach
                </ol>
            @endif
        </div>
    </div>

    {{-- ===================== QUICK LINKS ===================== --}}
    <div class="mt-6">
        <h3 class="mb-3 font-display text-lg font-bold text-bm-ink">Aksi Cepat ⚡</h3>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
            <a href="{{ route('admin.albums.create') }}" class="card card-hover flex items-center gap-3 !py-4">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-bm-purple/10 text-xl">💿</span>
                <span class="text-sm font-semibold text-bm-ink">Tambah Album</span>
            </a>
            <a href="{{ route('admin.news.create') }}" class="card card-hover flex items-center gap-3 !py-4">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-bm-pink/10 text-xl">📰</span>
                <span class="text-sm font-semibold text-bm-ink">Tulis Berita</span>
            </a>
            <a href="{{ route('admin.members.create') }}" class="card card-hover flex items-center gap-3 !py-4">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-bm-mint/20 text-xl">🎤</span>
                <span class="text-sm font-semibold text-bm-ink">Tambah Member</span>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="card card-hover flex items-center gap-3 !py-4">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-bm-yellow/20 text-xl">🛍️</span>
                <span class="text-sm font-semibold text-bm-ink">Kelola Pesanan</span>
            </a>
        </div>
    </div>
</x-layouts.admin>
