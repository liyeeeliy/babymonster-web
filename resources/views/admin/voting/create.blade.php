<x-layouts.admin title="Buat Voting">
    @php
        $oldOpts = collect(old('options', []))->pluck('label')->filter(fn ($v) => $v !== null)->values()->all();
        if (count($oldOpts) < 2) {
            $oldOpts = array_pad($oldOpts, 2, '');
        }
        $curType = old('type', 'favorite_member');
    @endphp

    <div class="mb-6">
        <a href="{{ route('admin.voting.index') }}" class="text-sm font-semibold text-slate-400 hover:text-bm-purple">← Kembali ke daftar voting</a>
        <h2 class="mt-2 font-display text-2xl font-bold text-bm-ink">Buat Voting Baru 🗳️</h2>
        <p class="mt-1 text-sm text-slate-400">Bikin polling seru dan kasih minimal 2 pilihan untuk MONBEBE.</p>
    </div>

    <form method="POST" action="{{ route('admin.voting.store') }}" x-data="{ opts: @js($oldOpts) }">
        @csrf

        <div class="grid gap-6 lg:grid-cols-3">
            {{-- Kolom utama --}}
            <div class="space-y-5 lg:col-span-2">
                <div class="card space-y-5">
                    <div>
                        <label for="title" class="form-label">Judul Voting <span class="text-bm-pink">*</span></label>
                        <input type="text" id="title" name="title" class="form-input"
                               value="{{ old('title') }}" placeholder="Contoh: Siapa Member Favoritmu Bulan Ini?" required>
                        @error('title')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="type" class="form-label">Tipe Voting <span class="text-bm-pink">*</span></label>
                        <select id="type" name="type" class="form-select" required>
                            <option value="favorite_member" {{ $curType === 'favorite_member' ? 'selected' : '' }}>Member Favorit</option>
                            <option value="best_performance" {{ $curType === 'best_performance' ? 'selected' : '' }}>Penampilan Terbaik</option>
                            <option value="favorite_song" {{ $curType === 'favorite_song' ? 'selected' : '' }}>Lagu Favorit</option>
                        </select>
                        @error('type')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    {{-- Opsi dinamis --}}
                    <div>
                        <label class="form-label">Pilihan Voting <span class="text-bm-pink">*</span></label>
                        <p class="mb-2 text-xs text-slate-400">Minimal 2 pilihan. Klik "Tambah Opsi" untuk menambah lebih banyak. ✨</p>
                        <div class="space-y-2">
                            <template x-for="(opt, i) in opts" :key="i">
                                <div class="flex items-center gap-2">
                                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-bm-purple/10 text-sm font-bold text-bm-purple"
                                          x-text="i + 1"></span>
                                    <input type="text" name="options[][label]" x-model="opts[i]" class="form-input flex-1"
                                           :placeholder="'Pilihan ' + (i + 1)">
                                    <button type="button" x-show="opts.length > 2" @click="opts.splice(i, 1)"
                                            class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-rose-100 text-rose-600 transition hover:bg-rose-200"
                                            title="Hapus opsi">✕</button>
                                </div>
                            </template>
                        </div>
                        <button type="button" @click="opts.push('')" class="btn-outline btn-sm mt-3">➕ Tambah Opsi</button>
                        @error('options')<p class="form-error">{{ $message }}</p>@enderror
                        @error('options.*.label')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Kolom samping --}}
            <div class="space-y-5">
                <div class="card space-y-5">
                    <h3 class="font-display text-lg font-bold text-bm-ink">⏰ Periode Voting</h3>
                    <div>
                        <label for="starts_at" class="form-label">Mulai</label>
                        <input type="datetime-local" id="starts_at" name="starts_at" class="form-input"
                               value="{{ old('starts_at') }}">
                        <p class="mt-1 text-xs text-slate-400">Kosongkan untuk langsung mulai.</p>
                        @error('starts_at')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="ends_at" class="form-label">Berakhir</label>
                        <input type="datetime-local" id="ends_at" name="ends_at" class="form-input"
                               value="{{ old('ends_at') }}">
                        <p class="mt-1 text-xs text-slate-400">Kosongkan kalau tanpa batas waktu.</p>
                        @error('ends_at')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-3">
            <a href="{{ route('admin.voting.index') }}" class="btn-ghost">Batal</a>
            <button type="submit" class="btn-primary btn-lg">💾 Simpan Voting</button>
        </div>
    </form>
</x-layouts.admin>
