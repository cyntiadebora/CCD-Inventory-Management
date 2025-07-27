<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('item_variant_id'); // Foreign key ke item_variants

            $table->enum('transaction_type', ['in', 'out']); // Jenis transaksi: in atau out
            $table->integer('quantity');         // Jumlah yang masuk atau keluar
            $table->string('description')->nullable(); // Catatan opsional
            $table->timestamp('transaction_date')->useCurrent(); // default: now()

            $table->timestamps();

            $table->foreign('item_variant_id')->references('id')->on('item_variants')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};
