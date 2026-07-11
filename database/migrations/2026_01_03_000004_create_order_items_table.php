<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->morphs('purchasable'); // Album or Merchandise
            $table->string('name'); // snapshot nama produk saat dibeli
            $table->unsignedBigInteger('price'); // snapshot harga
            $table->unsignedInteger('quantity');
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->unsignedBigInteger('line_total');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
