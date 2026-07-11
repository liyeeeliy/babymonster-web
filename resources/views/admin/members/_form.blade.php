@php $member = $member ?? null; @endphp

<div class="grid gap-6 lg:grid-cols-3">
    {{-- Kolom utama --}}
    <div class="space-y-5 lg:col-span-2">
        <div class="card space-y-5">
            <div>
                <label for="name" class="form-label">Nama Member <span class="text-bm-pink">*</span></label>
                <input type="text" id="name" name="name" class="form-input"
                       value="{{ old('name', $member->name ?? '') }}" placeholder="Contoh: Ahyeon" required>
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="birth_date" class="form-label">Tanggal Lahir</label>
                    <input type="date" id="birth_date" name="birth_date" class="form-input"
                           value="{{ old('birth_date', optional($member->birth_date ?? null)->format('Y-m-d')) }}">
                    @error('birth_date')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="position" class="form-label">Posisi</label>
                    <input type="text" id="position" name="position" class="form-input"
                           value="{{ old('position', $member->position ?? '') }}" placeholder="Main Vocalist, Leader...">
                    @error('position')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="nationality" class="form-label">Kebangsaan</label>
                    <input type="text" id="nationality" name="nationality" class="form-input"
                           value="{{ old('nationality', $member->nationality ?? '') }}" placeholder="Korea, Thailand, Jepang...">
                    @error('nationality')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="sort_order" class="form-label">Urutan Tampil</label>
                    <input type="number" id="sort_order" name="sort_order" min="0" class="form-input"
                           value="{{ old('sort_order', $member->sort_order ?? 0) }}" placeholder="0">
                    @error('sort_order')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="description" class="form-label">Deskripsi</label>
                <textarea id="description" name="description" rows="4" class="form-textarea"
                          placeholder="Ceritakan tentang member ini...">{{ old('description', $member->description ?? '') }}</textarea>
                @error('description')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="facts_text" class="form-label">Fun Facts</label>
                <textarea id="facts_text" name="facts_text" rows="5" class="form-textarea"
                          placeholder="Satu fakta per baris&#10;Suka makan tteokbokki&#10;Jago dance&#10;Bisa main gitar">{{ old('facts_text', implode("\n", $member->facts ?? [])) }}</textarea>
                <p class="mt-1 text-xs text-slate-400">Tulis satu fakta menarik per baris. ✨</p>
                @error('facts_text')<p class="form-error">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    {{-- Kolom samping --}}
    <div class="space-y-5">
        <div class="card space-y-3">
            <label for="photo" class="form-label">Foto Member</label>
            @if ($member?->photo)
                <x-cover :src="$member->photo" :label="$member->name" emoji="🎤" rounded="rounded-2xl" class="aspect-[3/4] w-full" />
                <p class="text-xs text-slate-400">Unggah file baru untuk mengganti foto saat ini.</p>
            @endif
            <input type="file" id="photo" name="photo" accept="image/*" class="form-input">
            <p class="text-xs text-slate-400">Gambar (maks 4 MB). Rasio potret 3:4 disarankan.</p>
            @error('photo')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div class="card">
            <label class="flex items-center gap-3">
                <input type="checkbox" name="is_active" value="1"
                       class="h-5 w-5 rounded-md border-bm-purple/30 text-bm-purple focus:ring-bm-purple"
                       {{ old('is_active', $member->is_active ?? true) ? 'checked' : '' }}>
                <span class="text-sm font-semibold text-bm-ink">Aktifkan member (tampil di situs)</span>
            </label>
            @error('is_active')<p class="form-error">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-3">
    <a href="{{ route('admin.members.index') }}" class="btn-ghost">Batal</a>
    <button type="submit" class="btn-primary btn-lg">💾 Simpan</button>
</div>
