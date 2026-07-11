<x-layouts.app title="Checkout">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        {{-- ===================== HEADER ===================== --}}
        <div class="mb-8 text-center sm:text-left">
            <span class="section-kicker">Langkah Terakhir</span>
            <h1 class="section-title">Checkout 🧾</h1>
            <p class="mt-2 max-w-xl text-slate-400">
                Isi alamat pengiriman dan pilih kurir. Ongkir otomatis dihitung, ya! ✨
            </p>
        </div>

        <div x-data="{
                subtotal: {{ (int) $subtotal }},
                shipping: 0,
                rp(n) { return 'Rp' + Number(n).toLocaleString('id-ID'); },
                get total() { return this.subtotal + this.shipping; },
            }"
             x-init="$nextTick(() => shipping = Number($refs.courier?.selectedOptions[0]?.dataset.cost || 0))"
             class="grid gap-6 lg:grid-cols-3">

            {{-- ===================== FORM (KIRI) ===================== --}}
            <form method="POST" action="{{ route('checkout.store') }}" class="space-y-6 lg:col-span-2">
                @csrf

                <div class="card space-y-4">
                    <h2 class="font-display text-lg font-bold text-bm-ink">🚚 Alamat Pengiriman</h2>

                    <div>
                        <label for="recipient_name" class="form-label">Nama Penerima</label>
                        <input type="text" id="recipient_name" name="recipient_name"
                               value="{{ old('recipient_name', auth()->user()->name ?? '') }}"
                               class="form-input" placeholder="Nama lengkap penerima" required autofocus>
                        @error('recipient_name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="phone" class="form-label">Nomor HP</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                               class="form-input" placeholder="0812xxxxxxxx" required>
                        @error('phone')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="address" class="form-label">Alamat Lengkap</label>
                        <textarea id="address" name="address" rows="3" class="form-textarea"
                                  placeholder="Nama jalan, nomor rumah, kelurahan, kecamatan, kota, kode pos" required>{{ old('address') }}</textarea>
                        @error('address')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="courier" class="form-label">Kurir Pengiriman</label>
                        <select id="courier" name="courier" x-ref="courier" required
                                @change="shipping = Number($event.target.selectedOptions[0].dataset.cost || 0)"
                                class="form-select">
                            <option value="" data-cost="0" disabled {{ old('courier') ? '' : 'selected' }}>— Pilih kurir —</option>
                            @foreach ($couriers as $key => $cost)
                                <option value="{{ $key }}" data-cost="{{ $cost }}"
                                        {{ old('courier') === (string) $key ? 'selected' : '' }}>
                                    {{ $key }} — Rp{{ number_format($cost, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        @error('courier')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <button type="submit" class="btn-primary btn-block btn-lg">Buat Pesanan 🎉</button>
                <a href="{{ route('cart.index') }}" class="btn-ghost btn-block btn-sm">← Kembali ke Keranjang</a>
            </form>

            {{-- ===================== RINGKASAN (KANAN) ===================== --}}
            <div>
                <div class="card lg:sticky lg:top-24">
                    <h2 class="font-display text-lg font-bold text-bm-ink">Ringkasan Pesanan</h2>

                    <div class="mt-4 space-y-3">
                        @foreach ($items as $item)
                            @php $lineTotal = $item->purchasable->price * $item->quantity; @endphp
                            <div class="flex items-center gap-3">
                                <x-cover :src="$item->purchasable->cover" :label="$item->purchasable->name"
                                         emoji="💿" rounded="rounded-xl" class="h-12 w-12 shrink-0" />
                                <div class="min-w-0 flex-1">
                                    <p class="line-clamp-1 text-sm font-semibold text-bm-ink">{{ $item->purchasable->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $item->quantity }} × Rp{{ number_format($item->purchasable->price, 0, ',', '.') }}</p>
                                </div>
                                <span class="shrink-0 text-sm font-semibold text-bm-ink">
                                    Rp{{ number_format($lineTotal, 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="my-4 border-t border-dashed border-white/10"></div>

                    <div class="space-y-2.5 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Subtotal</span>
                            <span class="font-semibold text-bm-ink">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Ongkir</span>
                            <span class="font-semibold text-bm-ink" x-text="shipping > 0 ? rp(shipping) : 'Pilih kurir dulu'">Pilih kurir dulu</span>
                        </div>
                    </div>

                    <div class="my-4 border-t border-dashed border-white/10"></div>

                    <div class="flex items-center justify-between font-display text-lg font-bold">
                        <span class="text-bm-ink">Total Bayar</span>
                        <span class="text-bm-purple" x-text="rp(total)">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>

                    <p class="mt-3 rounded-2xl bg-bm-yellow/15 px-4 py-3 text-xs text-amber-200">
                        💡 Ongkir menyesuaikan kurir yang kamu pilih. Total di atas ikut berubah otomatis.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
