<x-layouts.app title="Pesanan Saya">
    <div class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
        {{-- ===================== HEADER ===================== --}}
        <div class="mb-8 text-center sm:text-left">
            <span class="section-kicker">Riwayat Belanja</span>
            <h1 class="section-title">Pesanan Saya 📦</h1>
            <p class="mt-2 max-w-xl text-slate-400">
                Pantau status semua pesananmu di sini, MONBEBE! 💜
            </p>
        </div>

        @if ($orders->isEmpty())
            <x-empty-state emoji="📦" title="Belum ada pesanan"
                           message="Kamu belum pernah checkout. Yuk mulai koleksi album pertamamu!">
                <x-slot:action>
                    <a href="{{ route('store.index') }}" class="btn-primary btn-lg">🛍️ Belanja Sekarang →</a>
                </x-slot:action>
            </x-empty-state>
        @else
            <div class="space-y-4">
                @foreach ($orders as $order)
                    <div class="card flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-2xl bg-bm-purple/10 text-xl">🧾</span>
                                <div>
                                    <p class="font-display font-bold text-bm-ink">{{ $order->order_number }}</p>
                                    <p class="text-xs text-slate-400">
                                        {{ optional($order->created_at)->translatedFormat('d M Y · H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-4 sm:justify-end">
                            <x-order-status :status="$order->status" />
                            <div class="text-right">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Total</p>
                                <p class="font-display text-lg font-bold text-bm-purple">
                                    Rp{{ number_format($order->total, 0, ',', '.') }}
                                </p>
                            </div>
                            <a href="{{ route('orders.show', $order) }}" class="btn-outline btn-sm">Lihat Detail →</a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
