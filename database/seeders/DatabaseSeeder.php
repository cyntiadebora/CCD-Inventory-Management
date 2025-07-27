<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       $this->call([
        SizeSeeder::class,              // Jika `ItemSeeder` butuh size dari sini
        ItemSeeder::class,              // Butuh size (clothing/shoes) → letakkan setelah SizeSeeder jika ada ketergantungan
        UserSeeder::class,              // Data user untuk assign ke relasi
        ItemVariantSeeder::class,
        
        
    ]);
    }
}
