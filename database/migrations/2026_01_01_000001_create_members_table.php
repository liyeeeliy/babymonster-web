<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->date('birth_date')->nullable();
            $table->string('position')->nullable(); // Leader, Vocalist, Rapper, Dancer, Maknae, dst
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->json('facts')->nullable(); // array of fakta unik
            $table->string('nationality')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
