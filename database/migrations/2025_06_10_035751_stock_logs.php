<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('item_variant_id'); // foreign key ke item_variants

            $table->date('log_date'); // tanggal awal bulan, misalnya 2025-06-01

            $table->integer('opening_stock')->default(0); // stok awal bulan
            $table->integer('stock_in')->default(0);      // jumlah masuk selama bulan itu
            $table->integer('stock_out')->default(0);     // jumlah keluar selama bulan itu
            $table->integer('closing_stock')->default(0); // hasil perhitungan akhir bulan

            $table->timestamps();

            $table->foreign('item_variant_id')->references('id')->on('item_variants')->onDelete('cascade');

            $table->unique(['item_variant_id', 'log_date']); // agar 1 variant tidak punya 2 log di bulan yg sama
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_logs');
    }
};
