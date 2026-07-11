<x-layouts.app title="Keranjang">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        {{-- ===================== HEADER ===================== --}}
        <div class="mb-8 text-center sm:text-left">
            <span class="section-kicker">Keranjang Kamu</span>
            <h1 class="section-title">Keranjang Belanja 🛒</h1>
            <p class="mt-2 max-w-xl text-slate-400">
                Cek lagi koleksi yang mau kamu bawa pulang, MONBEBE! 💜
            </p>
        </div>

        @if ($items->isEmpty())
            <x-empty-state emoji="🛒" title="Keranjang masih kosong"
                           message="Yuk isi keranjangmu dengan album-album kece BABYMONSTER!">
                <x-slot:action>
                    <a href="{{ route('store.index') }}" class="btn-primary btn-lg">🛍️ Jelajahi Album →</a>
                </x-slot:action>
            </x-empty-state>
        @else
            <div class="grid gap-6 lg:grid-cols-3">
                {{-- ===================== DAFTAR ITEM ===================== --}}
                <div class="space-y-4 lg:col-span-2">
                    @foreach ($items as $item)
                        @php $lineTotal = $item->purchasable->price * $item->quantity; @endphp
                        <div class="card flex flex-col gap-4 sm:flex-row sm:items-center">
                            <a href="{{ route('store.show', $item->purchasable) }}" class="shrink-0 self-center">
                                <x-cover :src="$item->purchasable->cover" :label="$item->purchasable->name"
                                         emoji="💿" rounded="rounded-2xl" class="h-24 w-24" />
                            </a>

                            <div class="min-w-0 flex-1">
                                <a href="{{ route('store.show', $item->purchasable) }}"
                                   class="line-clamp-2 font-display font-bold text-bm-ink transition hover:text-bm-purple">
                                    {{ $item->purchasable->name }}
                                </a>
                                <p class="mt-1 text-sm font-semibold text-bm-purple">
                                    Rp{{ number_format($item->purchasable->price, 0, ',', '.') }}
                                    <span class="font-normal text-slate-400">/ item</span>
                                </p>
                                <p class="mt-0.5 text-xs text-slate-400">Stok tersedia: {{ $item->purchasable->stock }}</p>

                                <div class="mt-3 flex flex-wrap items-center gap-2">
                                    {{-- Update qty --}}
                                    <form method="POST" action="{{ route('cart.update', $item) }}"
                                          class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <label for="qty-{{ $item->id }}" class="sr-only">Jumlah</label>
                                        <input type="number" id="qty-{{ $item->id }}" name="quantity"
                                               value="{{ old('quantity', $item->quantity) }}"
                                               min="1" max="{{ $item->purchasable->stock }}"
                                               class="form-input w-20 text-center">
                                        <button type="submit" class="btn-outline btn-sm">Update</button>
                                    </form>

                                    {{-- Hapus --}}
                                    <form method="POST" action="{{ route('cart.destroy', $item) }}"
                                          onsubmit="return confirm('Hapus item ini dari keranjang?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus item"
                                                class="btn-ghost btn-sm text-rose-500 hover:bg-rose-50">🗑️ Hapus</button>
                                    </form>
                                </div>
                            </div>

                            <div class="shrink-0 text-right sm:w-32">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Subtotal</p>
                                <p class="font-display text-lg font-bold text-bm-purple">
                                    Rp{{ number_format($lineTotal, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- ===================== RINGKASAN ===================== --}}
                <div>
                    <div class="card lg:sticky lg:top-24">
                        <h2 class="font-display text-lg font-bold text-bm-ink">Ringkasan Belanja</h2>

                        <div class="mt-4 space-y-2.5 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">Total item</span>
                                <span class="font-semibold text-bm-ink">{{ $items->sum('quantity') }} item</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">Subtotal</span>
                                <span class="font-semibold text-bm-ink">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">Ongkir</span>
                                <span class="text-xs font-medium text-slate-400">Dihitung saat checkout</span>
                            </div>
                        </div>

                        <div class="my-4 border-t border-dashed border-white/10"></div>

                        <div class="flex items-center justify-between font-display text-lg font-bold">
                            <span class="text-bm-ink">Total</span>
                            <span class="text-bm-purple">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="btn-primary btn-block btn-lg mt-5">
                            Lanjut ke Checkout →
                        </a>
                        <a href="{{ route('store.index') }}" class="btn-ghost btn-block btn-sm mt-2">
                            ← Lanjut Belanja
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
