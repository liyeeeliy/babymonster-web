<x-layouts.app title="Detail Pesanan">
    @php
        $steps = [
            'pending'   => ['⏳', 'Menunggu Bayar'],
            'paid'      => ['💳', 'Dibayar'],
            'process'   => ['📦', 'Diproses'],
            'shipped'   => ['🚚', 'Dikirim'],
            'delivered' => ['✅', 'Selesai'],
        ];
        $stepIndex    = array_flip(array_keys($steps));
        $isCancelled  = $order->status === 'cancelled';
        $currentIndex = $isCancelled ? -1 : ($stepIndex[$order->status] ?? 0);

        $methodLabels = [
            'bank_transfer' => 'Transfer Bank',
            'ewallet'       => 'E-Wallet',
            'qris'          => 'QRIS',
        ];
    @endphp

    <div class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
        {{-- ===================== HEADER ===================== --}}
        <a href="{{ route('orders.index') }}" class="mb-4 inline-block text-sm font-semibold text-bm-purple hover:underline">
            ← Kembali ke Pesanan
        </a>
        <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <span class="section-kicker">Detail Pesanan</span>
                <h1 class="section-title">{{ $order->order_number }}</h1>
                <p class="mt-1 text-sm text-slate-400">
                    Dibuat {{ optional($order->created_at)->translatedFormat('d M Y · H:i') }}
                </p>
            </div>
            <x-order-status :status="$order->status" />
        </div>

        {{-- ===================== TIMELINE ===================== --}}
        <div class="card mb-6">
            @if ($isCancelled)
                <div class="flex items-center gap-3 rounded-2xl bg-rose-50 px-5 py-4 text-rose-600">
                    <span class="text-2xl">🚫</span>
                    <div>
                        <p class="font-display font-bold">Pesanan Dibatalkan</p>
                        <p class="text-sm text-rose-500/80">Pesanan ini sudah tidak diproses lagi.</p>
                    </div>
                </div>
            @else
                <div class="flex items-start">
                    @foreach ($steps as $key => [$icon, $label])
                        @php
                            $idx  = $stepIndex[$key];
                            $done = $idx <= $currentIndex;
                        @endphp
                        <div class="relative flex flex-1 flex-col items-center text-center">
                            @if (! $loop->last)
                                <div class="absolute left-1/2 top-5 h-1 w-full {{ $idx < $currentIndex ? 'bg-bm-purple' : 'bg-bm-purple/15' }}"></div>
                            @endif
                            <div @class([
                                    'relative z-10 grid h-10 w-10 place-items-center rounded-full text-lg',
                                    'bg-bm-purple text-white shadow-[var(--shadow-pop)]' => $done,
                                    'border-2 border-white/10 bg-white/5 text-slate-300' => ! $done,
                                ])>
                                {{ $icon }}
                            </div>
                            <p @class([
                                    'mt-2 text-[11px] font-semibold sm:text-xs',
                                    'text-bm-ink' => $done,
                                    'text-slate-400' => ! $done,
                                ])>{{ $label }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            {{-- ===================== KIRI: ITEM + TOTAL ===================== --}}
            <div class="space-y-6 lg:col-span-2">
                {{-- Items --}}
                <div class="card">
                    <h2 class="mb-4 font-display text-lg font-bold text-bm-ink">🛍️ Item Pesanan</h2>
                    <div class="divide-y divide-bm-purple/10">
                        @foreach ($order->items as $item)
                            <div class="flex items-center gap-3 py-3 first:pt-0 last:pb-0">
                                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-bm-purple/10 text-xl">💿</span>
                                <div class="min-w-0 flex-1">
                                    <p class="line-clamp-1 font-semibold text-bm-ink">{{ $item->name }}</p>
                                    <p class="text-xs text-slate-400">
                                        {{ $item->quantity }} × Rp{{ number_format($item->price, 0, ',', '.') }}
                                    </p>
                                </div>
                                <span class="shrink-0 font-display font-bold text-bm-ink">
                                    Rp{{ number_format($item->line_total, 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Totals --}}
                <div class="card">
                    <h2 class="mb-4 font-display text-lg font-bold text-bm-ink">🧾 Rincian Pembayaran</h2>
                    <div class="space-y-2.5 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Subtotal</span>
                            <span class="font-semibold text-bm-ink">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Ongkir ({{ $order->courier }})</span>
                            <span class="font-semibold text-bm-ink">Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        <div class="my-2 border-t border-dashed border-white/10"></div>
                        <div class="flex items-center justify-between font-display text-lg font-bold">
                            <span class="text-bm-ink">Total</span>
                            <span class="text-bm-purple">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===================== KANAN: AKSI + INFO ===================== --}}
            <div class="space-y-6">
                {{-- Aksi --}}
                <div class="card">
                    @if ($order->status === 'pending')
                        <p class="mb-3 text-sm text-slate-400">Pesanan menunggu pembayaran. Selesaikan sekarang, yuk!</p>
                        <a href="{{ route('checkout.pay', $order) }}" class="btn-primary btn-block btn-lg">💳 Bayar Sekarang</a>
                    @else
                        <p class="mb-3 text-sm text-slate-400">Butuh bukti pembayaran? Unduh invoice kamu di sini.</p>
                        <a href="{{ route('orders.invoice', $order) }}" class="btn-outline btn-block btn-lg">🧾 Lihat Invoice</a>
                    @endif
                </div>

                {{-- Pengiriman --}}
                <div class="card">
                    <h2 class="mb-3 font-display text-lg font-bold text-bm-ink">🚚 Pengiriman</h2>
                    <dl class="space-y-2 text-sm">
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Penerima</dt>
                            <dd class="font-semibold text-bm-ink">{{ $order->recipient_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">No. HP</dt>
                            <dd class="text-slate-300">{{ $order->phone }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Alamat</dt>
                            <dd class="text-slate-300">{{ $order->address }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Kurir</dt>
                            <dd class="text-slate-300">{{ $order->courier }}</dd>
                        </div>
                        @if ($order->tracking_number)
                            <div>
                                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">No. Resi</dt>
                                <dd class="font-display font-bold text-bm-purple">{{ $order->tracking_number }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                {{-- Pembayaran --}}
                @if ($order->payment)
                    <div class="card">
                        <h2 class="mb-3 font-display text-lg font-bold text-bm-ink">💳 Pembayaran</h2>
                        <dl class="space-y-2 text-sm">
                            <div class="flex items-center justify-between">
                                <dt class="text-slate-400">Metode</dt>
                                <dd class="font-semibold text-bm-ink">
                                    {{ $methodLabels[$order->payment->method] ?? ucfirst(str_replace('_', ' ', $order->payment->method)) }}
                                </dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-slate-400">Status</dt>
                                <dd><x-order-status :status="$order->payment->status" /></dd>
                            </div>
                            @if ($order->payment->reference)
                                <div class="flex items-center justify-between gap-3">
                                    <dt class="text-slate-400">Referensi</dt>
                                    <dd class="truncate font-mono text-xs text-slate-300">{{ $order->payment->reference }}</dd>
                                </div>
                            @endif
                            @if ($order->payment->paid_at)
                                <div class="flex items-center justify-between">
                                    <dt class="text-slate-400">Dibayar</dt>
                                    <dd class="text-slate-300">{{ optional($order->payment->paid_at)->translatedFormat('d M Y · H:i') }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
