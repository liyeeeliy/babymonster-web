@props(['status'])

@php
    $map = [
        'pending'   => ['Menunggu Bayar', 'chip-yellow'],
        'paid'      => ['Dibayar', 'chip-purple'],
        'process'   => ['Diproses', 'chip bg-cyan-500/15 text-cyan-200'],
        'shipped'   => ['Dikirim', 'chip bg-indigo-500/15 text-indigo-200'],
        'delivered' => ['Selesai', 'chip-mint'],
        'cancelled' => ['Dibatalkan', 'chip bg-rose-500/15 text-rose-200'],
    ];
    [$label, $cls] = $map[$status] ?? [ucfirst($status), 'chip-slate'];
@endphp

<span class="{{ $cls }}">{{ $label }}</span>
