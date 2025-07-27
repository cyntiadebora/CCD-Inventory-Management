<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::table('request_items', function (Blueprint $table) {
    $table->char('item_id', 36)->nullable()->after('request_id'); // <--- ini WAJIB char(36)
    $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
});

    }

    public function down(): void
{
    Schema::table('request_items', function (Blueprint $table) {
        if (Schema::hasColumn('request_items', 'item_id')) {
            $table->dropColumn('item_id'); // Drop saja kolom, kalau FK gak ada ya gak apa
        }
    });
}

};
