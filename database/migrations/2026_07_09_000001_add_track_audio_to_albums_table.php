<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('albums', function (Blueprint $table) {
            // URL/path MP3 per track, sejajar (index) dengan kolom tracklist.
            $table->json('track_audio')->nullable()->after('preview_audio');
        });
    }

    public function down(): void
    {
        Schema::table('albums', function (Blueprint $table) {
            $table->dropColumn('track_audio');
        });
    }
};
