<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_variants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('item_id'); // Foreign key ke items
            $table->unsignedBigInteger('size_id')->nullable(); // Nullable untuk item tanpa ukuran

           
            $table->integer('current_stock')->default(0);
            $table->integer('min_stock')->default(0);
            $table->integer('max_stock')->default(0);

            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('size_id')->references('id')->on('sizes')->onDelete('set null');

            $table->unique(['item_id', 'size_id']); // Kombinasi item + size harus unik
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_variants');
    }
};
