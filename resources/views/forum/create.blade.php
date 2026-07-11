<x-layouts.app title="Buat Post">
    <div class="mx-auto max-w-2xl px-4 py-12 sm:px-6 lg:px-8">
        {{-- ===================== BACK LINK ===================== --}}
        <a href="{{ route('forum.index') }}"
           class="mb-6 inline-flex items-center gap-2 text-sm font-semibold text-slate-400 hover:text-bm-purple">
            ← Kembali ke Forum
        </a>

        {{-- ===================== HEADER ===================== --}}
        <div class="mb-6">
            <span class="section-kicker">Mulai Obrolan</span>
            <h1 class="section-title">Buat Post Baru ✍️</h1>
            <p class="mt-2 text-slate-400">Bagikan pikiran, teori, atau momen favoritmu tentang BABYMONSTER! 💜</p>
        </div>

        {{-- ===================== FORM ===================== --}}
        <form method="POST" action="{{ route('forum.store') }}" enctype="multipart/form-data"
              class="card space-y-5" x-data="{ preview: null }">
            @csrf

            <div>
                <label for="title" class="form-label">Judul Post</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                       placeholder="Contoh: Comeback selanjutnya bakal kayak gimana ya?"
                       class="form-input" required autofocus>
                @error('title')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="content" class="form-label">Isi Post</label>
                <textarea name="content" id="content" rows="7"
                          placeholder="Tulis ceritamu di sini, MONBEBE..."
                          class="form-textarea" required>{{ old('content') }}</textarea>
                @error('content')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="photo" class="form-label">Foto <span class="font-normal text-slate-400">(opsional)</span></label>
                <input type="file" name="photo" id="photo" accept="image/*"
                       @change="const f = $event.target.files[0]; preview = f ? URL.createObjectURL(f) : null"
                       class="block w-full cursor-pointer rounded-2xl border-2 border-dashed border-bm-purple/20 bg-white/5 px-4 py-3 text-sm text-slate-400 file:mr-4 file:cursor-pointer file:rounded-full file:border-0 file:bg-bm-purple file:px-4 file:py-2 file:font-semibold file:text-white hover:file:bg-bm-purple-dark">
                @error('photo')<p class="form-error">{{ $message }}</p>@enderror

                <template x-if="preview">
                    <div class="mt-3 overflow-hidden rounded-2xl border border-white/10">
                        <img :src="preview" alt="Pratinjau foto" class="max-h-72 w-full object-cover">
                    </div>
                </template>
            </div>

            <div class="flex flex-wrap items-center justify-end gap-3 border-t border-white/10 pt-5">
                <a href="{{ route('forum.index') }}" class="btn-ghost">Batal</a>
                <button type="submit" class="btn-primary btn-lg">Terbitkan Post 🚀</button>
            </div>
        </form>
    </div>
</x-layouts.app>
