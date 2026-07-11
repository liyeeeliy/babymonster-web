<x-layouts.admin title="Tambah Member">
    <div class="mb-6">
        <a href="{{ route('admin.members.index') }}" class="text-sm font-semibold text-slate-400 hover:text-bm-purple">← Kembali ke daftar member</a>
        <h2 class="mt-2 font-display text-2xl font-bold text-bm-ink">Tambah Member Baru 🎤</h2>
        <p class="mt-1 text-sm text-slate-400">Lengkapi profil member BABYMONSTER.</p>
    </div>

    <form method="POST" action="{{ route('admin.members.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.members._form')
    </form>
</x-layouts.admin>
