<footer class="mt-16 border-t border-white/10 bg-white/[0.02]">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-8 md:grid-cols-4">
            <div class="md:col-span-2">
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-display text-2xl font-bold">
                    <span>👾</span> <span class="text-gradient">MONSTER HUB</span>
                </a>
                <p class="mt-3 max-w-sm text-sm text-slate-400">
                    Rumah digital untuk MONBEBE Indonesia — beli album, ikut voting, ngobrol di forum,
                    dan pantau kabar terbaru BABYMONSTER. 💜
                </p>
                <div class="mt-4 flex gap-2 text-xl">
                    <span class="grid h-9 w-9 place-items-center rounded-full bg-white/5">📸</span>
                    <span class="grid h-9 w-9 place-items-center rounded-full bg-white/5">🎬</span>
                    <span class="grid h-9 w-9 place-items-center rounded-full bg-white/5">🐦</span>
                </div>
            </div>
            <div>
                <h4 class="mb-3 font-display text-sm font-bold uppercase tracking-wider text-bm-ink">Jelajah</h4>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li><a href="{{ route('store.index') }}" class="hover:text-bm-pink">Toko Album</a></li>
                    <li><a href="{{ route('members.index') }}" class="hover:text-bm-pink">Member</a></li>
                    <li><a href="{{ route('news.index') }}" class="hover:text-bm-pink">Berita</a></li>
                    <li><a href="{{ route('voting.index') }}" class="hover:text-bm-pink">Voting</a></li>
                </ul>
            </div>
            <div>
                <h4 class="mb-3 font-display text-sm font-bold uppercase tracking-wider text-bm-ink">Komunitas</h4>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li><a href="{{ route('forum.index') }}" class="hover:text-bm-pink">Forum Diskusi</a></li>
                    <li><a href="{{ route('search') }}" class="hover:text-bm-pink">Pencarian</a></li>
                    @guest
                        <li><a href="{{ route('register') }}" class="hover:text-bm-pink">Gabung Sekarang</a></li>
                    @endguest
                </ul>
            </div>
        </div>
        <div class="mt-10 flex flex-col items-center justify-between gap-2 border-t border-white/10 pt-6 text-xs text-slate-400 sm:flex-row">
            <p>© {{ date('Y') }} MONSTER HUB. Fan project — proyek UAS Pemrograman Web 2.</p>
            <p>Dibuat dengan 💜 pakai Laravel & Tailwind CSS.</p>
        </div>
    </div>
</footer>
