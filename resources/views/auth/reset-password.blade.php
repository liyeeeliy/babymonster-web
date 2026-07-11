<x-layouts.guest title="Atur Password Baru 🔐">
    <p class="-mt-3 mb-6 text-center text-sm text-slate-400">
        Buat password baru untuk akun kamu, MONBEBE! 💜
    </p>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}"
                   class="form-input" placeholder="kamu@monbebe.id" required autofocus autocomplete="email">
            @error('email')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password" class="form-label">Password Baru</label>
            <input id="password" type="password" name="password"
                   class="form-input" placeholder="••••••••" required autocomplete="new-password">
            @error('password')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password_confirmation" class="form-label">Ulangi Password Baru</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   class="form-input" placeholder="••••••••" required autocomplete="new-password">
            @error('password_confirmation')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="btn-primary btn-block btn-lg">Simpan Password ✨</button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-300">
        <a href="{{ route('login') }}" class="font-semibold text-bm-purple hover:underline">Kembali ke Masuk</a>
    </p>
</x-layouts.guest>
