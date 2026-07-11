<x-layouts.guest title="Daftar 🎉">
    <p class="-mt-3 mb-6 text-center text-sm text-slate-400">
        Gabung jadi MONBEBE dan seru-seruan bareng! 💜
    </p>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="form-label">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                   class="form-input" placeholder="Nama kamu" required autofocus autocomplete="name">
            @error('name')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="username" class="form-label">Username</label>
            <input id="username" type="text" name="username" value="{{ old('username') }}"
                   class="form-input" placeholder="monbebe_kece" required autocomplete="username">
            <p class="mt-1 text-xs text-slate-400">Boleh huruf, angka, - dan _</p>
            @error('username')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-input" placeholder="kamu@monbebe.id" required autocomplete="email">
            @error('email')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password"
                   class="form-input" placeholder="••••••••" required autocomplete="new-password">
            @error('password')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password_confirmation" class="form-label">Ulangi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   class="form-input" placeholder="••••••••" required autocomplete="new-password">
            @error('password_confirmation')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="btn-primary btn-block btn-lg">Buat Akun 👾</button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-300">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-bm-purple hover:underline">Masuk di sini</a>
    </p>
</x-layouts.guest>
