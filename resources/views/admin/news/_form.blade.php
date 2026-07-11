@php $news = $news ?? null; @endphp

<div class="grid gap-6 lg:grid-cols-3">
    {{-- Kolom utama --}}
    <div class="space-y-5 lg:col-span-2">
        <div class="card space-y-5">
            <div>
                <label for="title" class="form-label">Judul Berita <span class="text-bm-pink">*</span></label>
                <input type="text" id="title" name="title" class="form-input"
                       value="{{ old('title', $news->title ?? '') }}" placeholder="Contoh: BABYMONSTER umumkan comeback!" required>
                @error('title')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="category" class="form-label">Kategori <span class="text-bm-pink">*</span></label>
                    <input type="text" id="category" name="category" class="form-input"
                           value="{{ old('category', $news->category ?? '') }}" placeholder="Comeback, Event, Award..." required>
                    @error('category')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="status" class="form-label">Status <span class="text-bm-pink">*</span></label>
                    <select id="status" name="status" class="form-select" required>
                        @php $curStatus = old('status', $news->status ?? 'draft'); @endphp
                        <option value="draft" {{ $curStatus === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ $curStatus === 'published' ? 'selected' : '' }}>Terbit</option>
                    </select>
                    @error('status')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="excerpt" class="form-label">Ringkasan</label>
                <textarea id="excerpt" name="excerpt" rows="3" class="form-textarea"
                          placeholder="Ringkasan singkat yang tampil di kartu berita...">{{ old('excerpt', $news->excerpt ?? '') }}</textarea>
                @error('excerpt')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="content" class="form-label">Isi Berita <span class="text-bm-pink">*</span></label>
                <textarea id="content" name="content" rows="12" class="form-textarea"
                          placeholder="Tulis isi lengkap berita di sini..." required>{{ old('content', $news->content ?? '') }}</textarea>
                @error('content')<p class="form-error">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    {{-- Kolom samping --}}
    <div class="space-y-5">
        <div class="card space-y-3">
            <label for="thumbnail" class="form-label">Thumbnail</label>
            @if ($news?->thumbnail)
                <x-cover :src="$news->thumbnail" :label="$news->title" emoji="📰" rounded="rounded-2xl" class="aspect-video w-full" />
                <p class="text-xs text-slate-400">Unggah file baru untuk mengganti thumbnail saat ini.</p>
            @endif
            <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="form-input">
            <p class="text-xs text-slate-400">Gambar (maks 4 MB). Rasio 16:9 disarankan.</p>
            @error('thumbnail')<p class="form-error">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-3">
    <a href="{{ route('admin.news.index') }}" class="btn-ghost">Batal</a>
    <button type="submit" class="btn-primary btn-lg">💾 Simpan</button>
</div>
