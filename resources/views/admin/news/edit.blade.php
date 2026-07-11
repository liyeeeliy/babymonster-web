<x-layouts.admin title="Edit Berita">
    <div class="mb-6">
        <a href="{{ route('admin.news.index') }}" class="text-sm font-semibold text-slate-400 hover:text-bm-purple">← Kembali ke daftar berita</a>
        <h2 class="mt-2 font-display text-2xl font-bold text-bm-ink">Edit Berita ✏️</h2>
        <p class="mt-1 text-sm text-slate-400 line-clamp-1">{{ $news->title }}</p>
    </div>

    <form method="POST" action="{{ route('admin.news.update', $news) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        @include('admin.news._form')
    </form>
</x-layouts.admin>
