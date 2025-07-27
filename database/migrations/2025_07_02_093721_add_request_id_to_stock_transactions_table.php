<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('request_id')->nullable()->after('item_variant_id'); // ✅ Tipe benar
            $table->foreign('request_id')
                ->references('id')
                ->on('requests')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->dropForeign(['request_id']);
            $table->dropColumn('request_id');
        });
    }
};
