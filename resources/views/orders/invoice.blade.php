<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $order->order_number }} · MONSTER HUB</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])

    <style>
        body { font-family: 'Poppins', system-ui, sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
            .invoice-sheet { box-shadow: none !important; border: none !important; }
        }
    </style>
</head>
<body class="bg-bm-cream text-bm-ink">
    @php
        $methodLabels = [
            'bank_transfer' => 'Transfer Bank',
            'ewallet'       => 'E-Wallet',
            'qris'          => 'QRIS',
        ];
    @endphp

    <div class="mx-auto max-w-3xl px-4 py-10">
        {{-- ===================== TOOLBAR (tidak ikut dicetak) ===================== --}}
        <div class="no-print mb-6 flex flex-wrap items-center justify-between gap-3">
            <a href="{{ route('orders.show', $order) }}" class="text-sm font-semibold text-bm-purple hover:underline">
                ← Kembali ke Detail Pesanan
            </a>
            <button type="button" onclick="window.print()" class="btn-primary">🖨️ Cetak / Simpan PDF</button>
        </div>

        {{-- ===================== LEMBAR INVOICE ===================== --}}
        <div class="invoice-sheet overflow-hidden rounded-3xl border border-white bg-white shadow-[var(--shadow-soft)]">
            <div class="h-2 w-full bg-gradient-to-r from-bm-purple via-bm-pink to-bm-yellow"></div>

            <div class="p-8 sm:p-10">
                {{-- Header --}}
                <div class="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <div class="flex items-center gap-2 font-display text-2xl font-bold text-bm-ink">
                            <span>👾</span> MONSTER HUB
                        </div>
                        <p class="mt-1 text-sm text-slate-500">Rumahnya MONBEBE Indonesia</p>
                        <p class="text-xs text-slate-400">store@monsterhub.id · monsterhub.id</p>
                    </div>
                    <div class="sm:text-right">
                        <p class="font-display text-3xl font-bold text-bm-purple">INVOICE</p>
                        <p class="mt-1 text-sm font-semibold text-bm-ink">{{ $order->order_number }}</p>
                        <p class="text-xs text-slate-400">
                            {{ optional($order->created_at)->translatedFormat('d M Y') }}
                        </p>
                        <div class="mt-2">
                            <x-order-status :status="$order->status" />
                        </div>
                    </div>
                </div>

                <div class="my-6 border-t border-dashed border-bm-purple/20"></div>

                {{-- Ditagihkan kepada --}}
                <div class="grid gap-6 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Ditagihkan Kepada</p>
                        <p class="mt-2 font-display font-bold text-bm-ink">{{ $order->recipient_name }}</p>
                        <p class="mt-1 text-sm text-slate-600">{{ $order->address }}</p>
                        <p class="text-sm text-slate-600">{{ $order->phone }}</p>
                        @if ($order->user)
                            <p class="text-sm text-slate-500">{{ $order->user->email }}</p>
                        @endif
                    </div>
                    <div class="sm:text-right">
                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Pengiriman</p>
                        <p class="mt-2 text-sm text-slate-600">Kurir: <span class="font-semibold text-bm-ink">{{ $order->courier }}</span></p>
                        @if ($order->tracking_number)
                            <p class="text-sm text-slate-600">Resi: <span class="font-semibold text-bm-ink">{{ $order->tracking_number }}</span></p>
                        @endif
                    </div>
                </div>

                {{-- Tabel item --}}
                <div class="mt-8 overflow-x-auto">
                    <table class="w-full min-w-[32rem] text-left text-sm">
                        <thead>
                            <tr class="border-b-2 border-bm-purple/15 text-xs font-semibold uppercase tracking-wide text-slate-400">
                                <th class="py-2 pr-3">#</th>
                                <th class="py-2 pr-3">Item</th>
                                <th class="py-2 pr-3 text-center">Qty</th>
                                <th class="py-2 pr-3 text-right">Harga</th>
                                <th class="py-2 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-bm-purple/10">
                            @foreach ($order->items as $item)
                                <tr>
                                    <td class="py-3 pr-3 text-slate-400">{{ $loop->iteration }}</td>
                                    <td class="py-3 pr-3 font-semibold text-bm-ink">{{ $item->name }}</td>
                                    <td class="py-3 pr-3 text-center text-slate-600">{{ $item->quantity }}</td>
                                    <td class="py-3 pr-3 text-right text-slate-600">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="py-3 text-right font-semibold text-bm-ink">Rp{{ number_format($item->line_total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Totals --}}
                <div class="mt-6 flex justify-end">
                    <div class="w-full max-w-xs space-y-2 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Subtotal</span>
                            <span class="font-semibold text-bm-ink">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Ongkir</span>
                            <span class="font-semibold text-bm-ink">Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        <div class="mt-2 flex items-center justify-between rounded-2xl bg-bm-purple/10 px-4 py-3 font-display text-lg font-bold">
                            <span class="text-bm-ink">Total</span>
                            <span class="text-bm-purple">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Info pembayaran --}}
                <div class="mt-8 rounded-2xl border border-bm-purple/15 bg-bm-cream/50 px-5 py-4">
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Status Pembayaran</p>
                    @if ($order->payment)
                        <div class="mt-2 flex flex-wrap items-center gap-x-6 gap-y-1 text-sm">
                            <span class="text-slate-600">
                                Metode:
                                <span class="font-semibold text-bm-ink">
                                    {{ $methodLabels[$order->payment->method] ?? ucfirst(str_replace('_', ' ', $order->payment->method)) }}
                                </span>
                            </span>
                            <span class="text-slate-600">Status: <x-order-status :status="$order->payment->status" /></span>
                            @if ($order->payment->paid_at)
                                <span class="text-slate-600">
                                    Dibayar: <span class="font-semibold text-bm-ink">{{ optional($order->payment->paid_at)->translatedFormat('d M Y · H:i') }}</span>
                                </span>
                            @endif
                            @if ($order->payment->reference)
                                <span class="text-slate-600">Ref: <span class="font-mono text-xs text-bm-ink">{{ $order->payment->reference }}</span></span>
                            @endif
                        </div>
                    @else
                        <p class="mt-2 text-sm text-slate-600">Belum ada pembayaran tercatat untuk pesanan ini.</p>
                    @endif
                </div>

                {{-- Footer --}}
                <div class="mt-8 border-t border-dashed border-bm-purple/20 pt-6 text-center">
                    <p class="font-display font-semibold text-bm-purple">Terima kasih sudah belanja, MONBEBE! 💜</p>
                    <p class="mt-1 text-xs text-slate-400">
                        Invoice ini dibuat otomatis oleh sistem MONSTER HUB · Simulasi transaksi untuk keperluan pembelajaran.
                    </p>
                </div>
            </div>
        </div>

        <p class="no-print mt-6 text-center text-xs text-slate-400">
            © {{ date('Y') }} MONSTER HUB · Proyek UAS Pemrograman Web 2
        </p>
    </div>
</body>
</html>
