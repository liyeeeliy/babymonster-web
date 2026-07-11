<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            // Pasangan label => nilai, mis. ['Nama Lengkap' => 'Kawai Ruka', ...]
            $table->json('biodata')->nullable()->after('gallery');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('biodata');
        });
    }
};
