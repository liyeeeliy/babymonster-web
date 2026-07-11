<x-layouts.admin title="Tulis Berita">
    <div class="mb-6">
        <a href="{{ route('admin.news.index') }}" class="text-sm font-semibold text-slate-400 hover:text-bm-purple">← Kembali ke daftar berita</a>
        <h2 class="mt-2 font-display text-2xl font-bold text-bm-ink">Tulis Berita Baru 📰</h2>
        <p class="mt-1 text-sm text-slate-400">Bagikan kabar terbaru BABYMONSTER ke MONBEBE.</p>
    </div>

    <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.news._form')
    </form>
</x-layouts.admin>
