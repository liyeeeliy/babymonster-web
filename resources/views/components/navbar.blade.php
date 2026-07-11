@php
    $links = [
        ['route' => 'home', 'label' => 'Beranda', 'active' => 'home'],
        ['route' => 'store.index', 'label' => 'Toko', 'active' => 'store.*'],
        ['route' => 'members.index', 'label' => 'Member', 'active' => 'members.*'],
        ['route' => 'news.index', 'label' => 'Berita', 'active' => 'news.*'],
        ['route' => 'forum.index', 'label' => 'Forum', 'active' => 'forum.*'],
        ['route' => 'voting.index', 'label' => 'Voting', 'active' => 'voting.*'],
    ];
    $cartCount = auth()->check() ? auth()->user()->cartItems()->count() : 0;
    $isStaff = auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isStaff());
@endphp

<header x-data="{ open: false, userMenu: false }" class="sticky top-0 z-40 bg-transparent">
    <nav class="mx-auto grid h-16 max-w-7xl grid-cols-[auto_1fr_auto] items-center gap-4 px-5 sm:px-8">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="font-display text-xl font-bold tracking-tight">
            <span class="text-white">Baby</span><span class="text-gradient">Monster</span>
        </a>

        {{-- Nav tengah --}}
        <div class="hidden items-center justify-center gap-1 lg:flex">
            @foreach ($links as $link)
                <a href="{{ route($link['route']) }}"
                   @class([
                       'rounded-full px-3.5 py-2 text-[13px] font-medium transition',
                       'bg-white/10 text-white' => request()->routeIs($link['active']),
                       'text-bm-muted hover:text-white' => ! request()->routeIs($link['active']),
                   ])>{{ $link['label'] }}</a>
            @endforeach
            @if ($isStaff)
                <a href="{{ route('admin.dashboard') }}"
                   @class([
                       'rounded-full px-3.5 py-2 text-[13px] font-medium transition',
                       'bg-white/10 text-white' => request()->routeIs('admin.*'),
                       'text-bm-muted hover:text-white' => ! request()->routeIs('admin.*'),
                   ])>Admin</a>
            @endif
        </div>

        {{-- Aksi kanan --}}
        <div class="flex items-center justify-end gap-1.5">
            {{-- Search --}}
            <a href="{{ route('search') }}" class="p-2 text-slate-300 transition hover:text-white" title="Cari" aria-label="Cari">
                <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="7" /><path d="m21 21-4.3-4.3" stroke-linecap="round" />
                </svg>
            </a>

            {{-- Keranjang --}}
            <a href="{{ auth()->check() ? route('cart.index') : route('login') }}" class="relative p-2 text-slate-300 transition hover:text-white" title="Keranjang" aria-label="Keranjang">
                <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z" />
                </svg>
                @if ($cartCount > 0)
                    <span class="absolute right-0 top-0 flex h-4 min-w-4 items-center justify-center rounded-full bg-bm-pink px-1 font-mono text-[10px] font-semibold text-white">{{ $cartCount }}</span>
                @endif
            </a>

            @auth
                {{-- Dropdown user --}}
                <div class="relative ml-1" @click.outside="userMenu = false">
                    <button @click="userMenu = !userMenu" class="avatar-ring h-9 w-9" aria-label="Menu akun">
                        <span class="flex h-full w-full items-center justify-center rounded-full bg-[var(--color-bm-cream)] font-display text-xs font-bold text-white">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    </button>
                    <div x-show="userMenu" x-cloak x-transition
                         class="absolute right-0 mt-3 w-52 overflow-hidden rounded-2xl border border-white/10 bg-[#160f28] p-2 shadow-2xl">
                        <div class="border-b border-white/10 px-3 pb-2 pt-1">
                            <p class="truncate text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                            <p class="truncate text-xs text-bm-muted">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.show') }}" class="mt-1 block rounded-xl px-3 py-2 text-sm font-medium text-slate-300 hover:bg-white/5 hover:text-white">Profil Saya</a>
                        <a href="{{ route('orders.index') }}" class="block rounded-xl px-3 py-2 text-sm font-medium text-slate-300 hover:bg-white/5 hover:text-white">Pesanan Saya</a>
                        @if ($isStaff)
                            <a href="{{ route('admin.dashboard') }}" class="block rounded-xl px-3 py-2 text-sm font-medium text-fuchsia-300 hover:bg-white/5">Dashboard Admin</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t border-white/10 pt-1">
                            @csrf
                            <button class="block w-full rounded-xl px-3 py-2 text-left text-sm font-medium text-rose-400 hover:bg-rose-500/10">Keluar</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn-primary ml-1 hidden rounded-full px-4 py-1.5 text-xs font-semibold sm:inline-flex">Masuk</a>
            @endauth

            {{-- Toggle mobile --}}
            <button @click="open = !open" class="p-2 text-slate-300 hover:text-white lg:hidden" aria-label="Menu">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                    <path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round" />
                </svg>
            </button>
        </div>
    </nav>

    {{-- Menu mobile --}}
    <div x-show="open" x-cloak x-transition class="border-t border-white/10 bg-[#160f28] px-5 py-3 lg:hidden">
        <div class="flex flex-col gap-1">
            @foreach ($links as $link)
                <a href="{{ route($link['route']) }}"
                   @class([
                       'rounded-xl px-4 py-2.5 text-[13px] font-medium',
                       'bg-white/10 text-white' => request()->routeIs($link['active']),
                       'text-bm-muted hover:text-white' => ! request()->routeIs($link['active']),
                   ])>{{ $link['label'] }}</a>
            @endforeach
            @if ($isStaff)
                <a href="{{ route('admin.dashboard') }}" class="rounded-xl px-4 py-2.5 text-[13px] font-medium text-fuchsia-300 hover:bg-white/5">Admin</a>
            @endif
            @guest
                <a href="{{ route('login') }}" class="btn-primary mt-1 rounded-full px-4 py-1.5 text-center text-xs font-semibold">Masuk</a>
            @endguest
        </div>
    </div>

    <style>[x-cloak]{display:none!important}</style>
</header>
