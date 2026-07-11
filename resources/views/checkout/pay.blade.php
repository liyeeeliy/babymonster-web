<x-layouts.app title="Pembayaran">
    @php
        // Nomor Virtual Account & referensi palsu (khusus simulasi)
        $digits  = preg_replace('/[^0-9]/', '', $order->order_number);
        $fakeVa  = '8808 8' . str_pad(substr($digits . '000000', 0, 6), 6, '0');
        $qrSeed  = md5($order->order_number);
        $qrN     = 15;
    @endphp

    <div class="mx-auto max-w-2xl px-4 py-12 sm:px-6 lg:px-8">
        {{-- ===================== HEADER ===================== --}}
        <div class="mb-6 text-center">
            <span class="section-kicker">Simulasi</span>
            <h1 class="section-title">Pembayaran 💳</h1>
            <p class="mt-2 text-slate-400">Selesaikan pembayaran untuk pesanan kamu, MONBEBE!</p>
        </div>

        {{-- ===================== KARTU TOTAL ===================== --}}
        <div class="overflow-hidden rounded-3xl bg-gradient-to-br from-bm-purple to-bm-pink p-6 text-center text-white shadow-[var(--shadow-pop)]">
            <p class="text-xs font-semibold uppercase tracking-widest text-white/70">No. Pesanan</p>
            <p class="font-display text-lg font-bold">{{ $order->order_number }}</p>
            <div class="mt-3 border-t border-white/20 pt-3">
                <p class="text-xs font-semibold uppercase tracking-widest text-white/70">Total Tagihan</p>
                <p class="font-display text-4xl font-bold">Rp{{ number_format($order->total, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="mt-4 rounded-2xl border border-amber-200 bg-bm-yellow/15 px-5 py-3 text-center text-sm font-medium text-amber-200">
            ⚠️ Ini simulasi — tidak ada transaksi nyata. Aman dicoba, ya! 💜
        </div>

        {{-- ===================== FORM METODE ===================== --}}
        <form method="POST" action="{{ route('checkout.confirm', $order) }}"
              x-data="{ method: '{{ old('payment_method', '') }}' }" class="mt-6">
            @csrf

            <div class="card space-y-3">
                <h2 class="font-display text-lg font-bold text-bm-ink">Pilih Metode Pembayaran</h2>

                @foreach ($methods as $key => $label)
                    <label class="flex cursor-pointer items-center gap-3 rounded-2xl border-2 px-4 py-3.5 transition"
                           :class="method === '{{ $key }}'
                                ? 'border-bm-purple bg-bm-purple/5 shadow-[var(--shadow-soft)]'
                                : 'border-white/10 hover:border-bm-purple/40'">
                        <input type="radio" name="payment_method" value="{{ $key }}" x-model="method"
                               class="h-4 w-4 accent-bm-purple" required>
                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-bm-purple/10 text-xl">
                            @switch($key)
                                @case('bank_transfer') 🏦 @break
                                @case('ewallet') 📱 @break
                                @case('qris') 🔳 @break
                                @default 💳
                            @endswitch
                        </span>
                        <span class="flex-1 font-semibold text-bm-ink">{{ $label }}</span>
                        <span x-show="method === '{{ $key }}'" x-cloak class="text-bm-purple">✓</span>
                    </label>
                @endforeach
                @error('payment_method')<p class="form-error">{{ $message }}</p>@enderror

                {{-- ===================== DETAIL DINAMIS ===================== --}}
                {{-- Transfer Bank --}}
                <div x-show="method === 'bank_transfer'" x-cloak x-transition
                     class="rounded-2xl border border-white/10 bg-bm-cream/60 p-5 text-center">
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Nomor Virtual Account</p>
                    <p class="mt-1 font-display text-2xl font-bold tracking-wider text-bm-ink">{{ $fakeVa }}</p>
                    <p class="mt-2 text-xs text-slate-400">Bank Monster Virtual · a.n. MONSTER HUB STORE</p>
                </div>

                {{-- E-Wallet --}}
                <div x-show="method === 'ewallet'" x-cloak x-transition
                     class="rounded-2xl border border-white/10 bg-bm-cream/60 p-5 text-center">
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Bayar via E-Wallet</p>
                    <div class="mt-3 flex flex-wrap justify-center gap-2">
                        <span class="chip-purple">OVO</span>
                        <span class="chip-pink">DANA</span>
                        <span class="chip-mint">GoPay</span>
                    </div>
                    <p class="mt-3 text-xs text-slate-400">
                        Kamu akan diarahkan ke aplikasi e-wallet untuk menyetujui pembayaran.
                    </p>
                </div>

                {{-- QRIS --}}
                <div x-show="method === 'qris'" x-cloak x-transition
                     class="rounded-2xl border border-white/10 bg-bm-cream/60 p-5 text-center">
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Scan QRIS</p>
                    <div class="mx-auto mt-3 w-44 rounded-2xl bg-white p-3 shadow-[var(--shadow-soft)]">
                        <div class="grid gap-px" style="grid-template-columns: repeat({{ $qrN }}, minmax(0, 1fr));">
                            @for ($i = 0; $i < $qrN * $qrN; $i++)
                                @php
                                    $r = intdiv($i, $qrN); $c = $i % $qrN;
                                    $corner = ($r < 3 && $c < 3) || ($r < 3 && $c >= $qrN - 3) || ($r >= $qrN - 3 && $c < 3);
                                    $edge   = ($r < 4 && $c < 4) || ($r < 4 && $c >= $qrN - 4) || ($r >= $qrN - 4 && $c < 4);
                                    $bit    = hexdec($qrSeed[$i % 32]) % 2;
                                    $dark   = $corner ? true : ($edge ? false : (bool) $bit);
                                @endphp
                                <div class="aspect-square {{ $dark ? 'bg-slate-900' : 'bg-white' }}"></div>
                            @endfor
                        </div>
                    </div>
                    <p class="mt-3 text-xs text-slate-400">Scan pakai aplikasi apa pun yang mendukung QRIS.</p>
                </div>
            </div>

            <button type="submit" class="btn-primary btn-block btn-lg mt-5">Bayar Sekarang 💜</button>
            <a href="{{ route('orders.index') }}" class="btn-ghost btn-block btn-sm mt-2">← Bayar Nanti / Lihat Pesanan</a>
        </form>
    </div>
</x-layouts.app>
