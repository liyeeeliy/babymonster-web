<x-layouts.admin title="Detail Pesanan">
    @php
        $methodLabels = [
            'bank_transfer' => 'Transfer Bank',
            'ewallet'       => 'E-Wallet',
            'qris'          => 'QRIS',
        ];
        $statusOptions = [
            'pending'   => 'Menunggu Bayar',
            'paid'      => 'Dibayar',
            'process'   => 'Diproses',
            'shipped'   => 'Dikirim',
            'delivered' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];
    @endphp

    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-slate-400 hover:text-bm-purple">← Kembali ke daftar pesanan</a>
        <div class="mt-2 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="font-display text-2xl font-bold text-bm-ink">{{ $order->order_number }}</h2>
                <p class="mt-1 text-sm text-slate-400">
                    Dibuat {{ optional($order->created_at)->translatedFormat('d M Y · H:i') }}
                </p>
            </div>
            <x-order-status :status="$order->status" />
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- ===================== KIRI: ITEM + TOTAL ===================== --}}
        <div class="space-y-6 lg:col-span-2">
            {{-- Items --}}
            <div class="card !p-0">
                <h2 class="border-b border-white/10 px-5 py-4 font-display text-lg font-bold text-bm-ink">🛍️ Item Pesanan</h2>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[520px] text-left text-sm">
                        <thead>
                            <tr class="border-b border-white/10 text-xs font-semibold uppercase tracking-wide text-slate-400">
                                <th class="px-5 py-3">Produk</th>
                                <th class="px-5 py-3 text-center">Qty</th>
                                <th class="px-5 py-3 text-right">Harga</th>
                                <th class="px-5 py-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-bm-purple/5">
                            @foreach ($order->items as $item)
                                <tr>
                                    <td class="px-5 py-3">
                                        <div class="flex items-center gap-3">
                                            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-2xl bg-bm-purple/10 text-lg">💿</span>
                                            <span class="font-semibold text-bm-ink">{{ $item->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3 text-center text-slate-300">{{ $item->quantity }}</td>
                                    <td class="px-5 py-3 text-right text-slate-300">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="px-5 py-3 text-right font-display font-bold text-bm-ink">Rp{{ number_format($item->line_total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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

        {{-- ===================== KANAN: STATUS + INFO ===================== --}}
        <div class="space-y-6">
            {{-- Update status --}}
            <div class="card">
                <h2 class="mb-4 font-display text-lg font-bold text-bm-ink">⚙️ Perbarui Status</h2>
                <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label for="status" class="form-label">Status Pesanan</label>
                        <select id="status" name="status" class="form-select">
                            @foreach ($statusOptions as $key => $label)
                                <option value="{{ $key }}" {{ old('status', $order->status) === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="tracking_number" class="form-label">No. Resi</label>
                        <input type="text" id="tracking_number" name="tracking_number" class="form-input"
                               value="{{ old('tracking_number', $order->tracking_number) }}" placeholder="Opsional, contoh: JNE123456789">
                        @error('tracking_number')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="btn-primary btn-block">💾 Perbarui Status</button>
                </form>
            </div>

            {{-- Pelanggan --}}
            <div class="card">
                <h2 class="mb-3 font-display text-lg font-bold text-bm-ink">👤 Pelanggan</h2>
                <dl class="space-y-2 text-sm">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Nama Akun</dt>
                        <dd class="font-semibold text-bm-ink">{{ $order->user?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Email</dt>
                        <dd class="text-slate-300">{{ $order->user?->email ?? '—' }}</dd>
                    </div>
                </dl>
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
</x-layouts.admin>
