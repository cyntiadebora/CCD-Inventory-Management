<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Item;
use App\Models\ItemVariant;
use App\Models\Size;
use App\Models\StockLog;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('item_variants')->truncate();
        DB::table('sizes')->truncate();
        DB::table('items')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $clothingSizes = ['S', 'M', 'L', 'XL'];
        $shoeSizes = ['36', '37', '38', '39', '40'];

        $items = [
            [
                'name' => 'Female Compression Top',
                'type' => 'Female',
                'has_size' => true,
                'category' => 'Clothing',
                'min_stock' => 5,
                'max_stock' => 50,
                'photo' => 'CompressionTop.jpg',
            ],
            [
                'name' => 'Wing',
                'type' => 'All',
                'has_size' => false,
                'category' => 'General',
                'min_stock' => 1,
                'max_stock' => 10,
                'photo' => 'Wing.jpg',
            ],
        ];

        foreach ($items as $itemData) {
            $uuid = (string) Str::uuid();
            $itemCode = 'ITEM-' . strtoupper(Str::random(5));

            $item = new Item();
            $item->id = $uuid;
            $item->code = $itemCode; // Field "code"
            $item->name = $itemData['name'];
            $item->type = $itemData['type'];
            $item->has_size = $itemData['has_size'];
            $item->photo = $itemData['photo'] ?? null;
            $item->created_at = now();
            $item->updated_at = now();
            $item->save();

            if ($item->has_size) {
                $sizes = $itemData['category'] === 'Clothing' ? $clothingSizes : $shoeSizes;

                foreach ($sizes as $sizeLabel) {
                    $size = Size::firstOrCreate(
                        [
                            'size_label' => $sizeLabel,
                            'category' => $itemData['category'],
                        ],
                        [
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );

                    $currentStock = rand(5, 15);

                    $variant = ItemVariant::create([
                        'item_id' => $item->id,
                        'size_id' => $size->id,
                        'min_stock' => $itemData['min_stock'],
                        'max_stock' => $itemData['max_stock'],
                        'variant_code' => $itemCode . '-' . $sizeLabel, // gabung code + size
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    StockLog::create([
                        'item_variant_id' => $variant->id,
                        'log_date' => now()->startOfMonth()->toDateString(),
                        'opening_stock' => $currentStock,
                        'stock_in' => 0,
                        'stock_out' => 0,
                        'closing_stock' => $currentStock,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } else {
                $currentStock = rand(5, 15);

                $variant = ItemVariant::create([
                    'item_id' => $item->id,
                    'size_id' => null,
                    'min_stock' => $itemData['min_stock'],
                    'max_stock' => $itemData['max_stock'],
                    'variant_code' => $itemCode, // gunakan code langsung
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                StockLog::create([
                    'item_variant_id' => $variant->id,
                    'log_date' => now()->startOfMonth()->toDateString(),
                    'opening_stock' => $currentStock,
                    'stock_in' => 0,
                    'stock_out' => 0,
                    'closing_stock' => $currentStock,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
