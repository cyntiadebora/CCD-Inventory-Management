<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->uuid('id')->primary(); // ✅ ini resmi UUID
            $table->string('code')->unique(); 
            $table->string('name');
            $table->enum('type', ['Male', 'Female', 'All']);
            $table->boolean('has_size')->default(true);
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
