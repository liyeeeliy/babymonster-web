<x-layouts.app title="Profil Saya">
    @push('head')
        <style>[x-cloak]{display:none!important}</style>
    @endpush

    @php $favId = old('favorite_member_id', optional($user->favoriteMember)->id); @endphp

    <div class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8" x-data="{ tab: 'profil' }">
        {{-- ===================== HEADER CARD ===================== --}}
        <div class="card mb-8 flex flex-col items-center gap-5 text-center sm:flex-row sm:text-left">
            <x-cover :src="$user->avatar" :label="$user->name" emoji="👤"
                     rounded="rounded-full" class="h-20 w-20 shrink-0" />
            <div class="min-w-0 flex-1">
                <h1 class="font-display text-2xl font-bold text-bm-ink">{{ $user->name }}</h1>
                <p class="text-sm font-semibold text-slate-400">&#64;{{ $user->username }}</p>
                <div class="mt-2 flex flex-wrap justify-center gap-2 sm:justify-start">
                    <span class="chip-purple">{{ ucfirst($user->role) }}</span>
                    @if ($user->is_active)
                        <span class="chip-mint">✅ Aktif</span>
                    @else
                        <span class="chip bg-rose-100 text-rose-600">Nonaktif</span>
                    @endif
                    @if ($user->favoriteMember)
                        <span class="chip-pink">💜 Bias: {{ $user->favoriteMember->name }}</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- ===================== TABS NAV ===================== --}}
        @php
            $tabs = [
                'profil' => '👤 Profil',
                'keamanan' => '🔒 Keamanan',
                'koleksi' => '💿 Koleksi',
                'pesanan' => '📦 Pesanan',
            ];
        @endphp
        <div class="no-scrollbar mb-6 flex gap-1 overflow-x-auto rounded-full border-2 border-white/10 bg-white/5 p-1">
            @foreach ($tabs as $key => $label)
                <button type="button" @click="tab = '{{ $key }}'"
                        class="shrink-0 rounded-full px-5 py-2 text-sm font-semibold transition"
                        :class="tab === '{{ $key }}' ? 'bg-bm-purple text-white shadow-[var(--shadow-pop)]' : 'text-slate-400 hover:text-bm-purple'">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        {{-- ===================== TAB: PROFIL ===================== --}}
        <div x-show="tab === 'profil'" x-cloak>
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="card space-y-5">
                @csrf
                @method('PATCH')

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                               class="form-input" placeholder="Nama kamu">
                        @error('name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}"
                               class="form-input" placeholder="username">
                        @error('username')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                           class="form-input" placeholder="kamu@monbebe.id">
                    @error('email')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="favorite_member_id" class="form-label">Member Favorit (Bias) 💜</label>
                    <select name="favorite_member_id" id="favorite_member_id" class="form-select">
                        <option value="">— Belum memilih —</option>
                        @foreach ($members as $m)
                            <option value="{{ $m->id }}" @selected((string) $favId === (string) $m->id)>{{ $m->name }}</option>
                        @endforeach
                    </select>
                    @error('favorite_member_id')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="bio" class="form-label">Bio</label>
                    <textarea name="bio" id="bio" rows="3" class="form-textarea"
                              placeholder="Ceritakan sedikit tentang dirimu, MONBEBE...">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="avatar" class="form-label">Foto Profil</label>
                    <input type="file" name="avatar" id="avatar" accept="image/*"
                           class="block w-full text-sm text-slate-400 file:mr-4 file:rounded-full file:border-0 file:bg-bm-purple/10 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-bm-purple hover:file:bg-bm-purple/20">
                    <p class="mt-1 text-xs text-slate-400">Format JPG/PNG, maksimal 2MB. Kosongkan jika tak ingin mengubah.</p>
                    @error('avatar')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="text-right">
                    <button type="submit" class="btn-primary">Simpan Perubahan 💾</button>
                </div>
            </form>
        </div>

        {{-- ===================== TAB: KEAMANAN ===================== --}}
        <div x-show="tab === 'keamanan'" x-cloak>
            <form method="POST" action="{{ route('profile.password') }}" class="card space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <h2 class="font-display text-lg font-bold text-bm-ink">Ubah Password 🔒</h2>
                    <p class="mt-1 text-sm text-slate-400">Demi keamanan akunmu, gunakan password yang kuat, ya!</p>
                </div>

                <div>
                    <label for="current_password" class="form-label">Password Saat Ini</label>
                    <input type="password" name="current_password" id="current_password"
                           class="form-input" placeholder="••••••••" autocomplete="current-password">
                    @error('current_password')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="password" class="form-label">Password Baru</label>
                        <input type="password" name="password" id="password"
                               class="form-input" placeholder="••••••••" autocomplete="new-password">
                        @error('password')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="form-input" placeholder="••••••••" autocomplete="new-password">
                        @error('password_confirmation')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn-primary">Perbarui Password 🔐</button>
                </div>
            </form>
        </div>

        {{-- ===================== TAB: KOLEKSI ===================== --}}
        <div x-show="tab === 'koleksi'" x-cloak>
            <div class="mb-4">
                <h2 class="font-display text-lg font-bold text-bm-ink">Koleksi Album 💿</h2>
                <p class="mt-1 text-sm text-slate-400">Semua album yang sudah kamu miliki, MONBEBE!</p>
            </div>

            @if ($purchasedAlbums->isEmpty())
                <x-empty-state emoji="💿" title="Belum punya album"
                               message="Kamu belum punya album apa pun. Yuk mulai koleksimu di toko!">
                    <x-slot:action>
                        <a href="{{ route('store.index') }}" class="btn-primary">🛍️ Jelajahi Album →</a>
                    </x-slot:action>
                </x-empty-state>
            @else
                <div class="grid grid-cols-2 gap-5 md:grid-cols-3 lg:grid-cols-4">
                    @foreach ($purchasedAlbums as $album)
                        <x-album-card :album="$album" />
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ===================== TAB: PESANAN ===================== --}}
        <div x-show="tab === 'pesanan'" x-cloak>
            <div class="mb-4 flex items-end justify-between">
                <div>
                    <h2 class="font-display text-lg font-bold text-bm-ink">Pesanan Terbaru 📦</h2>
                    <p class="mt-1 text-sm text-slate-400">Riwayat belanja terakhirmu.</p>
                </div>
                <a href="{{ route('orders.index') }}" class="btn-outline btn-sm hidden sm:inline-flex">Semua Pesanan →</a>
            </div>

            @if ($recentOrders->isEmpty())
                <x-empty-state emoji="📦" title="Belum ada pesanan"
                               message="Kamu belum pernah checkout. Yuk mulai koleksi album pertamamu!">
                    <x-slot:action>
                        <a href="{{ route('store.index') }}" class="btn-primary">🛍️ Belanja Sekarang →</a>
                    </x-slot:action>
                </x-empty-state>
            @else
                <div class="space-y-4">
                    @foreach ($recentOrders as $o)
                        <div class="card flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-center gap-3">
                                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-2xl bg-bm-purple/10 text-xl">🧾</span>
                                <div>
                                    <p class="font-display font-bold text-bm-ink">{{ $o->order_number }}</p>
                                    <p class="text-xs text-slate-400">{{ optional($o->created_at)->translatedFormat('d M Y · H:i') }}</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center justify-between gap-4 sm:justify-end">
                                <x-order-status :status="$o->status" />
                                <div class="text-right">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Total</p>
                                    <p class="font-display text-lg font-bold text-bm-purple">Rp{{ number_format($o->total, 0, ',', '.') }}</p>
                                </div>
                                <a href="{{ route('orders.show', $o) }}" class="btn-outline btn-sm">Detail →</a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 text-center sm:hidden">
                    <a href="{{ route('orders.index') }}" class="btn-primary">Lihat Semua Pesanan →</a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
