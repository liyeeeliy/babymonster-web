@if (session('success') || session('error') || $errors->any())
    <div class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-8">
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                 class="mb-3 flex items-center justify-between gap-3 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-5 py-3.5 text-sm font-medium text-emerald-200 backdrop-blur">
                <span>✅ {{ session('success') }}</span>
                <button @click="show = false" class="text-emerald-300/60 hover:text-emerald-200">✕</button>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition
                 class="mb-3 flex items-center justify-between gap-3 rounded-2xl border border-rose-500/20 bg-rose-500/10 px-5 py-3.5 text-sm font-medium text-rose-200 backdrop-blur">
                <span>⚠️ {{ session('error') }}</span>
                <button @click="show = false" class="text-rose-300/60 hover:text-rose-200">✕</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-3 rounded-2xl border border-rose-500/20 bg-rose-500/10 px-5 py-3.5 text-sm text-rose-200 backdrop-blur">
                <p class="font-semibold">Ups, ada yang perlu diperbaiki:</p>
                <ul class="mt-1 list-inside list-disc space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endif
