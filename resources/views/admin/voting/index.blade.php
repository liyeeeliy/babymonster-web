<x-layouts.admin title="Kelola Voting">
    @php
        $typeLabels = [
            'favorite_member'  => 'Member Favorit',
            'best_performance' => 'Penampilan Terbaik',
            'favorite_song'    => 'Lagu Favorit',
        ];
    @endphp

    {{-- Header --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <span class="section-kicker">Suara MONBEBE</span>
            <h2 class="font-display text-2xl font-bold text-bm-ink">Kelola Voting 🗳️</h2>
        </div>
        <a href="{{ route('admin.voting.create') }}" class="btn-primary">➕ Buat Voting</a>
    </div>

    @if ($polls->isEmpty())
        <x-empty-state emoji="🗳️" title="Belum ada voting" message="Buat polling seru biar MONBEBE bisa dukung favorit mereka!">
            <x-slot:action>
                <a href="{{ route('admin.voting.create') }}" class="btn-primary">➕ Buat Voting</a>
            </x-slot:action>
        </x-empty-state>
    @else
        <div class="card !p-0">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-white/10 text-xs font-semibold uppercase tracking-wide text-slate-400">
                            <th class="px-5 py-3">Judul Voting</th>
                            <th class="px-5 py-3">Tipe</th>
                            <th class="px-5 py-3">Total Suara</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-bm-purple/5">
                        @foreach ($polls as $poll)
                            <tr class="transition hover:bg-bm-purple/5">
                                <td class="px-5 py-3">
                                    <p class="font-semibold text-bm-ink">{{ $poll->title }}</p>
                                    @isset($poll->options)
                                        <p class="text-xs text-slate-400">{{ $poll->options->count() }} opsi pilihan</p>
                                    @endisset
                                </td>
                                <td class="px-5 py-3">
                                    <span class="chip-pink">{{ $typeLabels[$poll->type] ?? ucfirst(str_replace('_', ' ', $poll->type)) }}</span>
                                </td>
                                <td class="px-5 py-3 font-display font-bold text-bm-purple">
                                    {{ number_format($poll->votes_count) }}
                                </td>
                                <td class="px-5 py-3">
                                    @if ($poll->is_active)
                                        <span class="chip-mint">Aktif</span>
                                    @else
                                        <span class="chip-slate">Selesai</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('voting.results', $poll) }}" target="_blank"
                                           class="btn-outline btn-sm">📊 Hasil</a>
                                        <form method="POST" action="{{ route('admin.voting.destroy', $poll) }}"
                                              onsubmit="return confirm('Yakin ingin menghapus voting ini beserta semua suaranya?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-sm btn bg-rose-100 text-rose-600 hover:bg-rose-200">🗑️ Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-layouts.admin>
