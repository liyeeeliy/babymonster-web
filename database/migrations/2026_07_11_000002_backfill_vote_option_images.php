<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Seeder versi awal membuat opsi voting tanpa mengisi image. Salin
        // dari foto member yang namanya cocok dengan label opsi; hanya yang
        // masih kosong, agar gambar yang diubah lewat panel admin tidak
        // tertimpa.
        DB::table('vote_options')
            ->join('members', 'members.name', '=', 'vote_options.label')
            ->whereNull('vote_options.image')
            ->update(['vote_options.image' => DB::raw('members.photo')]);
    }

    public function down(): void
    {
        DB::table('vote_options')
            ->join('members', 'members.name', '=', 'vote_options.label')
            ->whereColumn('vote_options.image', 'members.photo')
            ->update(['vote_options.image' => null]);
    }
};
