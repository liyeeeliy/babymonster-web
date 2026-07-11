<x-layouts.admin title="Kelola Pesanan">
    @php
        $filters = [
            ''          => 'Semua',
            'pending'   => 'Menunggu Bayar',
            'paid'      => 'Dibayar',
            'process'   => 'Diproses',
            'shipped'   => 'Dikirim',
            'delivered' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];
        $active = request('status', '');
    @endphp

    {{-- Header --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <span class="section-kicker">Toko MONSTER HUB</span>
            <h2 class="font-display text-2xl font-bold text-bm-ink">Kelola Pesanan 🛍️</h2>
        </div>
        <a href="{{ route('admin.export.orders', request()->only('status')) }}" class="btn-outline">⬇️ Export CSV</a>
    </div>

    {{-- Filter status --}}
    <div class="mb-6 flex flex-wrap gap-2">
        @foreach ($filters as $key => $label)
            <a href="{{ route('admin.orders.index', $key === '' ? [] : ['status' => $key]) }}"
               @class([
                   'chip transition',
                   'bg-bm-purple text-white shadow-[var(--shadow-pop)]' => $active === $key,
                   'chip-slate hover:bg-bm-purple/10 hover:text-bm-purple' => $active !== $key,
               ])>
                {{ $label }}
            </a>
        @endforeach
    </div>

    @if ($orders->isEmpty())
        <x-empty-state emoji="🛍️" title="Belum ada pesanan"
            message="{{ $active ? 'Tidak ada pesanan dengan status ini.' : 'Pesanan dari MONBEBE akan tampil di sini.' }}" />
    @else
        <div class="card !p-0">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[820px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-white/10 text-xs font-semibold uppercase tracking-wide text-slate-400">
                            <th class="px-5 py-3">No. Pesanan</th>
                            <th class="px-5 py-3">Pelanggan</th>
                            <th class="px-5 py-3">Total</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Tanggal</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-bm-purple/5">
                        @foreach ($orders as $order)
                            <tr class="transition hover:bg-bm-purple/5">
                                <td class="px-5 py-3 font-display font-bold text-bm-ink">{{ $order->order_number }}</td>
                                <td class="px-5 py-3">
                                    <p class="font-semibold text-bm-ink">{{ $order->user?->name ?? $order->recipient_name }}</p>
                                    <p class="text-xs text-slate-400">{{ $order->user?->email ?? $order->phone }}</p>
                                </td>
                                <td class="px-5 py-3 font-semibold text-bm-purple">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                                <td class="px-5 py-3"><x-order-status :status="$order->status" /></td>
                                <td class="px-5 py-3 text-slate-400">{{ optional($order->created_at)->translatedFormat('d M Y') }}</td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn-outline btn-sm">👁️ Detail</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    @endif
</x-layouts.admin>
