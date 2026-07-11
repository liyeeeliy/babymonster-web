<x-layouts.app :title="$album->name">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        {{-- ===================== BREADCRUMB ===================== --}}
        <div class="mb-6 flex items-center gap-2 text-sm font-semibold text-slate-400">
            <a href="{{ route('store.index') }}" class="hover:text-bm-purple">Toko</a>
            <span>›</span>
            <span class="text-bm-ink">{{ $album->name }}</span>
        </div>

        {{-- ===================== DETAIL ===================== --}}
        <div class="grid gap-8 lg:grid-cols-2 lg:gap-12">
            {{-- LEFT: cover + player --}}
            <div class="space-y-6">
                <div class="overflow-hidden rounded-3xl shadow-[var(--shadow-pop)]">
                    <x-cover :src="$album->cover" :label="$album->name" emoji="💿"
                             rounded="rounded-3xl" class="aspect-square w-full" />
                </div>
                <x-album-player :album="$album" />
            </div>

            {{-- RIGHT: info + add to cart --}}
            <div>
                <span class="chip-yellow">💿 Album Resmi</span>
                <h1 class="mt-3 font-display text-3xl font-bold leading-tight text-bm-ink sm:text-4xl">
                    {{ $album->name }}
                </h1>

                <div class="mt-3">
                    <x-rating-stars :rating="$album->average_rating" :count="$album->reviews->count()" size="text-base" />
                </div>

                <p class="mt-5 font-display text-4xl font-bold text-bm-purple">
                    Rp{{ number_format($album->price, 0, ',', '.') }}
                </p>

                <div class="mt-5 flex flex-wrap items-center gap-3 text-sm">
                    @if (!is_null($album->release_date))
                        <span class="chip-slate">📅 Rilis {{ optional($album->release_date)->translatedFormat('d M Y') }}</span>
                    @endif
                    @if ($album->stock > 0)
                        <span class="chip-mint">✅ Stok {{ $album->stock }} tersedia</span>
                    @else
                        <span class="chip bg-rose-500 text-white">😢 Stok Habis</span>
                    @endif
                </div>

                @if (!empty($album->description))
                    <div class="prose-bm mt-6 text-slate-300">
                        {!! nl2br(e($album->description)) !!}
                    </div>
                @endif

                {{-- Add to cart --}}
                @auth
                    <form method="POST" action="{{ route('cart.store') }}"
                          x-data="{ qty: 1, max: {{ max($album->stock, 1) }} }"
                          class="mt-8 rounded-3xl border-2 border-white/10 bg-white/5 p-5">
                        @csrf
                        <input type="hidden" name="album_id" value="{{ $album->id }}">

                        @if ($album->stock > 0)
                            <div class="flex flex-wrap items-center gap-4">
                                <div>
                                    <label class="form-label">Jumlah</label>
                                    <div class="inline-flex items-center overflow-hidden rounded-full border-2 border-white/10 bg-white/5">
                                        <button type="button" @click="qty = Math.max(1, qty - 1)"
                                                class="grid h-11 w-11 place-items-center text-xl font-bold text-bm-purple transition hover:bg-bm-purple/5">−</button>
                                        <input type="number" name="quantity" x-model.number="qty"
                                               min="1" max="{{ $album->stock }}"
                                               class="w-14 border-0 bg-transparent text-center font-display text-lg font-bold text-bm-ink focus:outline-none focus:ring-0">
                                        <button type="button" @click="qty = Math.min(max, qty + 1)"
                                                class="grid h-11 w-11 place-items-center text-xl font-bold text-bm-purple transition hover:bg-bm-purple/5">+</button>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <span class="form-label block opacity-0">.</span>
                                    <button type="submit" class="btn-primary btn-lg btn-block">🛒 Tambah ke Keranjang</button>
                                </div>
                            </div>
                        @else
                            <p class="text-center text-sm text-slate-400">Album ini sedang habis. Nantikan restock-nya, ya! 💜</p>
                            <button type="button" disabled
                                    class="btn-primary btn-lg btn-block mt-3 cursor-not-allowed opacity-50">
                                Stok Habis
                            </button>
                        @endif
                    </form>
                @else
                    <div class="mt-8 rounded-3xl border-2 border-dashed border-bm-purple/20 bg-bm-purple/5 p-6 text-center">
                        <p class="text-4xl animate-float">🛒</p>
                        <p class="mt-2 text-sm text-slate-300">Mau checkout album ini? Masuk dulu yuk, MONBEBE! 💜</p>
                        <div class="mt-4 flex flex-wrap justify-center gap-2">
                            <a href="{{ route('login') }}" class="btn-primary">Masuk</a>
                            <a href="{{ route('register') }}" class="btn-outline">Daftar Gratis</a>
                        </div>
                    </div>
                @endauth
            </div>
        </div>

        {{-- ===================== ULASAN ===================== --}}
        <section class="mt-16">
            <div class="mb-6 flex items-end justify-between">
                <div>
                    <span class="section-kicker">Kata MONBEBE</span>
                    <h2 class="section-title">Ulasan ⭐</h2>
                </div>
                <div class="hidden sm:block">
                    <x-rating-stars :rating="$album->average_rating" :count="$album->reviews->count()" size="text-lg" />
                </div>
            </div>

            <div class="grid gap-8 lg:grid-cols-3">
                {{-- List --}}
                <div class="lg:col-span-2">
                    @if ($album->reviews->isEmpty())
                        <x-empty-state emoji="⭐" title="Belum ada ulasan"
                                       message="Jadi yang pertama berbagi pendapat tentang album ini!" />
                    @else
                        <div class="space-y-4">
                            @foreach ($album->reviews as $review)
                                <div class="card flex gap-4">
                                    <div class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-bm-purple/10 font-display font-bold text-bm-purple">
                                        {{ mb_strtoupper(mb_substr($review->user->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center justify-between gap-2">
                                            <h4 class="font-display font-bold text-bm-ink">{{ $review->user->name ?? 'MONBEBE' }}</h4>
                                            <x-rating-stars :rating="$review->rating" size="text-xs" />
                                        </div>
                                        @if (!empty($review->review))
                                            <p class="mt-1 text-sm text-slate-300">{{ $review->review }}</p>
                                        @else
                                            <p class="mt-1 text-sm italic text-slate-400">Memberi rating tanpa komentar.</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Form --}}
                <div class="lg:col-span-1">
                    @auth
                        <form method="POST" action="{{ route('store.review', $album) }}"
                              class="card sticky top-24"
                              x-data="{ rating: {{ (int) old('rating', 5) }}, hover: 0 }">
                            @csrf
                            <h3 class="font-display text-lg font-bold text-bm-ink">Tulis Ulasanmu ✍️</h3>
                            <p class="mt-1 text-sm text-slate-400">Bagikan pendapatmu tentang album ini.</p>

                            <div class="mt-4">
                                <label class="form-label">Rating kamu</label>
                                <input type="hidden" name="rating" :value="rating">
                                <div class="flex items-center gap-1">
                                    <template x-for="star in 5" :key="star">
                                        <button type="button" @click="rating = star"
                                                @mouseenter="hover = star" @mouseleave="hover = 0"
                                                class="text-3xl transition hover:scale-110"
                                                :class="(hover || rating) >= star ? 'text-bm-yellow' : 'text-slate-300'">★</button>
                                    </template>
                                    <span class="ml-2 text-sm font-semibold text-slate-400" x-text="rating + ' / 5'"></span>
                                </div>
                                @error('rating')<p class="form-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="mt-4">
                                <label for="review" class="form-label">Komentar <span class="font-normal text-slate-400">(opsional)</span></label>
                                <textarea name="review" id="review" rows="4"
                                          placeholder="Album ini keren banget karena..."
                                          class="form-textarea">{{ old('review') }}</textarea>
                                @error('review')<p class="form-error">{{ $message }}</p>@enderror
                            </div>

                            <button type="submit" class="btn-primary btn-block mt-4">Kirim Ulasan 💜</button>
                        </form>
                    @else
                        <div class="card text-center">
                            <p class="text-4xl animate-float">⭐</p>
                            <h3 class="mt-2 font-display text-lg font-bold text-bm-ink">Mau kasih ulasan?</h3>
                            <p class="mt-1 text-sm text-slate-400">Masuk dulu untuk menilai album ini, MONBEBE!</p>
                            <a href="{{ route('login') }}" class="btn-primary btn-block mt-4">Masuk untuk Menulis</a>
                        </div>
                    @endauth
                </div>
            </div>
        </section>
    </div>
</x-layouts.app>
