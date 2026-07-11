@props(['title' => 'Dashboard'])

@php
    $nav = [
        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => '📊', 'active' => 'admin.dashboard'],
        ['route' => 'admin.albums.index', 'label' => 'Album', 'icon' => '💿', 'active' => 'admin.albums.*'],
        ['route' => 'admin.news.index', 'label' => 'Berita', 'icon' => '📰', 'active' => 'admin.news.*'],
        ['route' => 'admin.members.index', 'label' => 'Member', 'icon' => '🎤', 'active' => 'admin.members.*'],
        ['route' => 'admin.voting.index', 'label' => 'Voting', 'icon' => '🗳️', 'active' => 'admin.voting.*'],
        ['route' => 'admin.orders.index', 'label' => 'Pesanan', 'icon' => '🛍️', 'active' => 'admin.orders.*'],
        ['route' => 'admin.activity-log.index', 'label' => 'Log Aktivitas', 'icon' => '📝', 'active' => 'admin.activity-log.*'],
    ];
@endphp

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} · Admin MONSTER HUB</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@400;600;700;900&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen" x-data="{ sidebar: false }">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full transform border-r border-white/10 bg-white/[0.03] backdrop-blur-xl transition-transform lg:static lg:translate-x-0"
            :class="sidebar && '!translate-x-0'"
        >
            <div class="flex h-16 items-center gap-2 border-b border-white/10 px-6 font-display text-xl font-bold text-bm-ink">
                <span>👾</span> Admin Panel
            </div>
            <nav class="space-y-1 p-4">
                @foreach ($nav as $item)
                    <a href="{{ route($item['route']) }}"
                       @class([
                           'flex items-center gap-3 rounded-2xl px-4 py-2.5 text-sm font-semibold transition',
                           'bg-gradient-to-r from-bm-purple to-bm-pink text-white shadow-[var(--shadow-pop)]' => request()->routeIs($item['active']),
                           'text-slate-400 hover:bg-white/5 hover:text-white' => ! request()->routeIs($item['active']),
                       ])>
                        <span>{{ $item['icon'] }}</span> {{ $item['label'] }}
                    </a>
                @endforeach

                @if (auth()->user()?->isAdmin())
                    <a href="{{ route('admin.users.index') }}"
                       @class([
                           'flex items-center gap-3 rounded-2xl px-4 py-2.5 text-sm font-semibold transition',
                           'bg-gradient-to-r from-bm-purple to-bm-pink text-white shadow-[var(--shadow-pop)]' => request()->routeIs('admin.users.*'),
                           'text-slate-400 hover:bg-white/5 hover:text-white' => ! request()->routeIs('admin.users.*'),
                       ])>
                        <span>👥</span> Kelola User
                    </a>
                @endif
            </nav>

            <div class="absolute bottom-0 w-full border-t border-white/10 p-4">
                <a href="{{ route('home') }}" class="mb-2 flex items-center gap-3 rounded-2xl px-4 py-2 text-sm font-semibold text-slate-400 hover:bg-white/5 hover:text-white">
                    <span>🏠</span> Lihat Situs
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="flex w-full items-center gap-3 rounded-2xl px-4 py-2 text-sm font-semibold text-rose-400 hover:bg-rose-500/10">
                        <span>🚪</span> Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- Overlay mobile -->
        <div x-show="sidebar" @click="sidebar = false" x-cloak class="fixed inset-0 z-30 bg-black/60 lg:hidden"></div>

        <!-- Konten -->
        <div class="flex flex-1 flex-col">
            <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-white/10 bg-bm-cream/70 px-4 backdrop-blur-xl sm:px-8">
                <div class="flex items-center gap-3">
                    <button @click="sidebar = !sidebar" class="rounded-xl p-2 text-bm-purple hover:bg-white/5 lg:hidden">☰</button>
                    <h1 class="font-display text-xl font-bold text-bm-ink">{{ $title }}</h1>
                </div>
                <div class="flex items-center gap-3">
                    <span class="chip-purple hidden sm:inline-flex">{{ ucfirst(auth()->user()?->role) }}</span>
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-bm-purple to-bm-pink font-bold text-white">
                        {{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-8">
                <x-flash />
                {{ $slot }}
            </main>
        </div>
    </div>

    <style>[x-cloak]{display:none!important}</style>
</body>
</html>
