<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;
use App\Models\Size;
use Illuminate\Support\Facades\DB;

class UserItemSizeSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel agar tidak terjadi duplikasi
        DB::table('user_item_sizes')->truncate();

        $items = Item::all();
        $users = User::where('role', 'cabin_crew')->get();

        // Daftar ukuran berdasarkan kategori size (bukan item)
        $sizeMap = [
            'Clothing' => ['S', 'M', 'L', 'XL'],
            'Shoes' => ['36', '37', '38', '39', '40', '41', '42'],
        ];

        foreach ($users as $user) {
            foreach ($items as $item) {
                // Cek gender item (type) vs gender user
                if (
                    $item->type !== 'All' &&
                    strtolower($item->type) !== strtolower($user->gender)
                ) {
                    continue;
                }

                // Item harus memiliki size
                if (!$item->has_size) {
                    continue;
                }

                foreach ($sizeMap as $category => $labels) {
                    foreach ($labels as $sizeLabel) {
                        $size = Size::where('size_label', $sizeLabel)
                                    ->where('category', $category)
                                    ->first();

                        if (!$size) continue;

                        // Cari item_variant
                        $variant = DB::table('item_variants')
                            ->where('item_id', $item->id)
                            ->where('size_id', $size->id)
                            ->first();

                        if (!$variant) continue;

                        // Cek apakah user sudah punya variant ini
                        $alreadyExists = DB::table('user_item_sizes')
                            ->where('user_id', $user->id)
                            ->where('item_variant_id', $variant->id)
                            ->exists();

                        if ($alreadyExists) continue;

                        echo "INSERT: user {$user->id}, variant {$variant->id} (item {$item->id}, size {$size->id})\n";

                        DB::table('user_item_sizes')->insert([
                            'user_id' => $user->id,
                            'item_variant_id' => $variant->id,
                            'quantity' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        break 2; // Stop setelah satu size cocok ditemukan
                    }
                }
            }
        }
    }
}
