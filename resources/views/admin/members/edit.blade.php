<x-layouts.admin title="Edit Member">
    <div class="mb-6">
        <a href="{{ route('admin.members.index') }}" class="text-sm font-semibold text-slate-400 hover:text-bm-purple">← Kembali ke daftar member</a>
        <h2 class="mt-2 font-display text-2xl font-bold text-bm-ink">Edit Member: {{ $member->name }} ✏️</h2>
        <p class="mt-1 text-sm text-slate-400">Perbarui profil member di bawah ini.</p>
    </div>

    <form method="POST" action="{{ route('admin.members.update', $member) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        @include('admin.members._form')
    </form>
</x-layouts.admin>
