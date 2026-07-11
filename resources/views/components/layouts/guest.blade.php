@props(['title' => null])

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title . ' · MONSTER HUB' : 'MONSTER HUB' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@400;600;700;900&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-md">
            <div class="mb-8 text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 font-display text-3xl font-bold">
                    <span class="animate-float">👾</span> <span class="text-gradient">MONSTER HUB</span>
                </a>
                <p class="mt-2 text-sm text-slate-400">Rumahnya MONBEBE Indonesia</p>
            </div>

            <div class="card !p-8">
                @if ($title)
                    <h1 class="mb-6 text-center font-display text-2xl font-bold text-bm-ink">{{ $title }}</h1>
                @endif
                {{ $slot }}
            </div>

            <p class="mt-6 text-center text-xs text-slate-300">© {{ date('Y') }} MONSTER HUB · Proyek UAS Pemrograman Web 2</p>
        </div>
    </div>
</body>
</html>
