<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Size;

class SizeSeeder extends Seeder
{
    public function run(): void
    {
        $clothingSizes = ['S', 'M', 'L', 'XL'];
        $shoeSizes = ['36', '37', '38', '39', '40', '41', '42'];

        foreach ($clothingSizes as $label) {
            Size::firstOrCreate(
                ['size_label' => $label, 'category' => 'Clothing'],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        foreach ($shoeSizes as $label) {
            Size::firstOrCreate(
                ['size_label' => $label, 'category' => 'Shoes'],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        // Tambahkan ukuran umum jika perlu
        $generalSizes = ['One Size', 'Free Size'];
        foreach ($generalSizes as $label) {
            Size::firstOrCreate(
                ['size_label' => $label, 'category' => 'General'],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
