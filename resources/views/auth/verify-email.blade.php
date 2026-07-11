<x-layouts.guest title="Verifikasi Email 📬">
    <div class="mb-4 text-center text-5xl animate-float">💌</div>

    <p class="mb-6 text-center text-sm leading-relaxed text-slate-300">
        Hampir selesai, MONBEBE! Kami sudah mengirim link verifikasi ke email kamu.
        Silakan cek kotak masuk (atau folder spam ya) dan klik link-nya untuk mengaktifkan akun. ✨
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-5 rounded-2xl border border-bm-mint/40 bg-bm-mint/15 px-4 py-3 text-center text-sm font-medium text-emerald-200">
            Link verifikasi baru sudah dikirim ke email kamu! 💜
        </div>
    @endif

    <div class="rounded-2xl border-2 border-dashed border-bm-purple/25 bg-bm-purple/5 p-4 text-center text-xs text-slate-400">
        Belum menerima email? Coba tunggu beberapa menit lalu cek kembali kotak masuk kamu. 👾
    </div>

    <form method="POST" action="{{ route('logout') }}" class="mt-6">
        @csrf
        <button type="submit" class="btn-outline btn-block">Keluar</button>
    </form>
</x-layouts.guest>
