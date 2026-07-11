@php $album = $album ?? null; @endphp

<div class="grid gap-6 lg:grid-cols-3">
    {{-- Kolom utama --}}
    <div class="space-y-5 lg:col-span-2">
        <div class="card space-y-5">
            <div>
                <label for="name" class="form-label">Nama Album <span class="text-bm-pink">*</span></label>
                <input type="text" id="name" name="name" class="form-input"
                       value="{{ old('name', $album->name ?? '') }}" placeholder="Contoh: BABYMONS7ER" required>
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="price" class="form-label">Harga (Rp) <span class="text-bm-pink">*</span></label>
                    <input type="number" id="price" name="price" min="0" class="form-input"
                           value="{{ old('price', $album->price ?? '') }}" placeholder="185000" required>
                    @error('price')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="stock" class="form-label">Stok <span class="text-bm-pink">*</span></label>
                    <input type="number" id="stock" name="stock" min="0" class="form-input"
                           value="{{ old('stock', $album->stock ?? '') }}" placeholder="100" required>
                    @error('stock')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="release_date" class="form-label">Tanggal Rilis</label>
                <input type="date" id="release_date" name="release_date" class="form-input"
                       value="{{ old('release_date', optional($album->release_date ?? null)->format('Y-m-d')) }}">
                @error('release_date')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="description" class="form-label">Deskripsi</label>
                <textarea id="description" name="description" rows="4" class="form-textarea"
                          placeholder="Ceritakan tentang album ini...">{{ old('description', $album->description ?? '') }}</textarea>
                @error('description')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="tracklist_text" class="form-label">Tracklist</label>
                <textarea id="tracklist_text" name="tracklist_text" rows="5" class="form-textarea"
                          placeholder="Satu judul lagu per baris&#10;SHEESH&#10;BATTER UP&#10;STUCK IN THE MIDDLE">{{ old('tracklist_text', implode("\n", $album->tracklist ?? [])) }}</textarea>
                <p class="mt-1 text-xs text-slate-400">Tulis satu judul lagu per baris. 🎵</p>
                @error('tracklist_text')<p class="form-error">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    {{-- Kolom samping --}}
    <div class="space-y-5">
        {{-- Cover --}}
        <div class="card space-y-3">
            <label for="cover" class="form-label">Cover Album</label>
            @if ($album?->cover)
                <x-cover :src="$album->cover" :label="$album->name" emoji="💿" rounded="rounded-2xl" class="h-40 w-full" />
                <p class="text-xs text-slate-400">Unggah file baru untuk mengganti cover saat ini.</p>
            @endif
            <input type="file" id="cover" name="cover" accept="image/*" class="form-input">
            @error('cover')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Audio Preview --}}
        <div class="card space-y-3">
            <label for="preview_audio" class="form-label">Audio Preview</label>
            @if ($album?->preview_audio)
                <p class="chip-mint">🎧 Audio sudah diunggah</p>
                <p class="text-xs text-slate-400">Unggah file baru untuk mengganti preview.</p>
            @endif
            <input type="file" id="preview_audio" name="preview_audio" accept="audio/*" class="form-input">
            <p class="text-xs text-slate-400">Format: mp3, wav, ogg (maks 16 MB).</p>
            @error('preview_audio')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Status --}}
        <div class="card">
            <label class="flex items-center gap-3">
                <input type="checkbox" name="is_active" value="1"
                       class="h-5 w-5 rounded-md border-bm-purple/30 text-bm-purple focus:ring-bm-purple"
                       {{ old('is_active', $album->is_active ?? true) ? 'checked' : '' }}>
                <span class="text-sm font-semibold text-bm-ink">Aktifkan album (tampil di toko)</span>
            </label>
            @error('is_active')<p class="form-error">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-3">
    <a href="{{ route('admin.albums.index') }}" class="btn-ghost">Batal</a>
    <button type="submit" class="btn-primary btn-lg">💾 Simpan</button>
</div>
