<x-layouts.admin title="Edit Album">
    <div class="mb-6">
        <a href="{{ route('admin.albums.index') }}" class="text-sm font-semibold text-slate-400 hover:text-bm-purple">← Kembali ke daftar album</a>
        <h2 class="mt-2 font-display text-2xl font-bold text-bm-ink">Edit Album: {{ $album->name }} ✏️</h2>
        <p class="mt-1 text-sm text-slate-400">Perbarui detail album di bawah ini.</p>
    </div>

    <form method="POST" action="{{ route('admin.albums.update', $album) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        @include('admin.albums._form')
    </form>
</x-layouts.admin>
