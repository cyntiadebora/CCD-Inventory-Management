<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('history_record_items', function (Blueprint $table) {
            $table->id();

            // Tetap gunakan foreignId karena history_record_id adalah bigInt
            $table->foreignId('history_record_id')->constrained('history_records')->onDelete('cascade');

            // GANTI dari foreignId ke string untuk item_id
            $table->string('item_id');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');

            $table->integer('quantity')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('history_record_items', function (Blueprint $table) {
            // Drop foreign key dengan nama kolom
            $table->dropForeign(['history_record_id']);
            $table->dropForeign(['item_id']);
        });

        Schema::dropIfExists('history_record_items');
    }
};
