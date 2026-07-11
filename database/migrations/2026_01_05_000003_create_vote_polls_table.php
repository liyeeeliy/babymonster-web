<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vote_polls', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Favorite Member, Best Performance, Favorite Song
            $table->enum('type', ['favorite_member', 'best_performance', 'favorite_song']);
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('vote_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vote_poll_id')->constrained()->cascadeOnDelete();
            $table->string('label'); // nama member / judul performance / judul lagu
            $table->string('image')->nullable();
            $table->unsignedInteger('vote_count')->default(0);
            $table->timestamps();
        });

        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vote_poll_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vote_option_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['vote_poll_id', 'user_id']); // satu akun satu suara per poll
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
        Schema::dropIfExists('vote_options');
        Schema::dropIfExists('vote_polls');
    }
};
