<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Item;
use App\Models\Size;

class ItemVariantSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('item_variants')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $items = Item::where('has_size', true)->get();

        foreach ($items as $item) {
            // Ambil hanya ukuran yang sesuai dengan kategori item
            $matchedSizes = Size::where('category', $item->category)->get();

            foreach ($matchedSizes as $size) {
                DB::table('item_variants')->insert([
                    'id' => Str::uuid(),
                    'item_id' => $item->id,
                    'size_id' => $size->id,
                    'variant_code' => strtoupper($item->code . '-' . $size->size_label),
                    'current_stock' => 10,
                    'min_stock' => 5,
                    'max_stock' => 20,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

