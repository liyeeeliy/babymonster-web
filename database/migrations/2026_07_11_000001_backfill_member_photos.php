<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Seeder versi awal membuat member tanpa mengisi photo, padahal
        // file fotonya sudah ada di public/img/members/{slug}.jpg. Isi yang
        // masih kosong saja, agar foto yang diunggah lewat panel admin
        // tidak tertimpa.
        DB::table('members')->whereNull('photo')->update([
            'photo' => DB::raw("CONCAT('/img/members/', slug, '.jpg')"),
        ]);
    }

    public function down(): void
    {
        DB::table('members')
            ->where('photo', DB::raw("CONCAT('/img/members/', slug, '.jpg')"))
            ->update(['photo' => null]);
    }
};
