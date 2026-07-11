<x-layouts.guest title="Masuk 👋">
    <p class="-mt-3 mb-6 text-center text-sm text-slate-400">
        Selamat datang kembali, MONBEBE! 💜
    </p>

    {{-- Status sesi (mis. setelah reset password) --}}
    @if (session('status'))
        <div class="mb-5 rounded-2xl border border-bm-mint/40 bg-bm-mint/15 px-4 py-3 text-sm font-medium text-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-input" placeholder="kamu@monbebe.id" required autofocus autocomplete="username">
            @error('email')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password"
                   class="form-input" placeholder="••••••••" required autocomplete="current-password">
            @error('password')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center justify-between">
            <label for="remember" class="flex cursor-pointer items-center gap-2 text-sm text-slate-300">
                <input id="remember" type="checkbox" name="remember"
                       class="h-4 w-4 rounded border-bm-purple/30 text-bm-purple focus:ring-bm-purple/30">
                Ingat saya
            </label>
            <a href="{{ route('password.request') }}" class="text-sm font-semibold text-bm-pink hover:underline">
                Lupa password?
            </a>
        </div>

        <button type="submit" class="btn-primary btn-block btn-lg">Masuk ✨</button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-300">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold text-bm-purple hover:underline">Daftar dulu yuk!</a>
    </p>

    {{-- Info akun demo --}}
    <div class="mt-6 rounded-2xl border-2 border-dashed border-bm-purple/25 bg-bm-purple/5 p-4">
        <p class="mb-2 flex items-center gap-1.5 text-xs font-bold uppercase tracking-wide text-bm-purple">
            🔑 Akun Demo
        </p>
        <ul class="space-y-1 text-xs text-slate-300">
            <li>
                <span class="chip chip-pink !py-0.5">Admin</span>
                <code class="font-mono text-slate-200">admin@monsterhub.test</code> / <code class="font-mono text-slate-200">password</code>
            </li>
            <li>
                <span class="chip chip-mint !py-0.5">Fans</span>
                <code class="font-mono text-slate-200">fan@monsterhub.test</code> / <code class="font-mono text-slate-200">password</code>
            </li>
        </ul>
    </div>
</x-layouts.guest>
