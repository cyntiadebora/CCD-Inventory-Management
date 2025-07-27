<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['Clothing', 'Shoes', 'General']); // Lebih konsisten dan aman pakai enum
            $table->string('size_label'); // Contoh: S, M, 36, One Size
            $table->timestamps();

            $table->unique(['size_label', 'category']); // kombinasi unik
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sizes');
    }
};
