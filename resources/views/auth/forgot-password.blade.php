<x-layouts.guest title="Lupa Password? 🔑">
    <p class="-mt-3 mb-6 text-center text-sm text-slate-400">
        Tenang, MONBEBE! Masukkan email kamu dan kami akan mengirim link untuk mengatur ulang password. ✨
    </p>

    {{-- Status sesi (link reset terkirim) --}}
    @if (session('status'))
        <div class="mb-5 rounded-2xl border border-bm-mint/40 bg-bm-mint/15 px-4 py-3 text-sm font-medium text-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-input" placeholder="kamu@monbebe.id" required autofocus autocomplete="email">
            @error('email')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="btn-primary btn-block btn-lg">Kirim Link Reset 💌</button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-300">
        Ingat password kamu?
        <a href="{{ route('login') }}" class="font-semibold text-bm-purple hover:underline">Kembali ke Masuk</a>
    </p>
</x-layouts.guest>
