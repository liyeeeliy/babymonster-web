<x-layouts.admin title="Tambah Album">
    <div class="mb-6">
        <a href="{{ route('admin.albums.index') }}" class="text-sm font-semibold text-slate-400 hover:text-bm-purple">← Kembali ke daftar album</a>
        <h2 class="mt-2 font-display text-2xl font-bold text-bm-ink">Tambah Album Baru 💿</h2>
        <p class="mt-1 text-sm text-slate-400">Lengkapi detail album untuk ditampilkan di toko.</p>
    </div>

    <form method="POST" action="{{ route('admin.albums.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.albums._form')
    </form>
</x-layouts.admin>
