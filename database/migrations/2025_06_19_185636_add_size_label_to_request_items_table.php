<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('request_items', function (Blueprint $table) {
        $table->string('size_label')->nullable()->after('item_variant_id');
    });
}

public function down(): void
{
    Schema::table('request_items', function (Blueprint $table) {
        $table->dropColumn('size_label');
    });
}

};
