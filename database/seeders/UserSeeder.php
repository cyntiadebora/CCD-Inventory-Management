<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;
use App\Models\Size;
use App\Models\ItemVariant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'              => 'Admin',
                'email_verified_at' => now(),
                'role'              => 'admin',
                'status'            => 'active',
                'gender'            => 'male',
                'base'              => 'CGK',
                'join_date'         => '2023-01-01',
                'photo'             => 'admin.jpg',
                'password'          => Hash::make('admin'),
                'remember_token'    => null,
                'id_number'         => $this->generateIdNumber(),
            ]
        );

        // Cabin Crew 1
        User::updateOrCreate(
            ['email' => 'dionadrew@gmail.com'],
            [
                'name'              => 'Dion Andrew',
                'email_verified_at' => now(),
                'role'              => 'cabin_crew',
                'status'            => 'active',
                'gender'            => 'male',
                'base'              => 'CGK',
                'join_date'         => '2023-02-15',
                'rank'              => 'Senior Cabin Crew',
                'batch'             => 2,
                'photo'             => 'crewmale1.jpg',
                'password'          => Hash::make('cabin'),
                'remember_token'    => null,
                'id_number'         => $this->generateIdNumber(),
            ]
        );

        // Tambahan 5 Cabin Crew
        $cabinCrews = [
            [
                'name' => 'Andi Wijaya',
                'email' => 'andi.wijaya@example.com',
                'gender' => 'male',
                'base' => 'SUB',
                'join_date' => '2022-05-10',
                'rank' => 'Junior Cabin Crew',
                'batch' => 5,
                'photo' => 'crewmale2.jpg',
            ],
            [
                'name' => 'Putri Ayu',
                'email' => 'putri.ayu@example.com',
                'gender' => 'female',
                'base' => 'CGK',
                'join_date' => '2021-11-20',
                'rank' => 'Senior Cabin Crew',
                'batch' => 3,
                'photo' => 'crew1.jpg',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'gender' => 'male',
                'base' => 'DPS',
                'join_date' => '2020-07-30',
                'rank' => 'Chief Cabin Crew',
                'batch' => 1,
                'photo' => 'crewmale3.jpg',
            ],
            [
                'name' => 'Sari Melati',
                'email' => 'sari.melati@example.com',
                'gender' => 'female',
                'base' => 'KNO',
                'join_date' => '2023-03-12',
                'rank' => 'Junior Cabin Crew',
                'batch' => 6,
                'photo' => 'crew2.jpg',
            ],
            [
                'name' => 'Sabrina Pratama',
                'email' => 'rizky.pratama@example.com',
                'gender' => 'female',
                'base' => 'UPG',
                'join_date' => '2022-08-05',
                'rank' => 'Cabin Crew',
                'batch' => 4,
                'photo' => 'crew3.jpg',
            ],
        ];

        foreach ($cabinCrews as $crew) {
            $firstName = strtolower(explode(' ', $crew['name'])[0]);

            User::updateOrCreate(
                ['email' => $crew['email']],
                array_merge($crew, [
                    'email_verified_at' => now(),
                    'role' => 'cabin_crew',
                    'status' => 'active',
                    'password' => Hash::make($firstName),
                    'remember_token' => null,
                    'id_number' => $this->generateIdNumber(),
                ])
            );
        }

        // Dummy data item dan size
        $items = [
            'Female Compression Top' => Item::where('name', 'Female Compression Top')->first(),
            'Female Red Skirt' => Item::where('name', 'Female Red Skirt')->first(),
            'Male White Shirt' => Item::where('name', 'Male White Shirt')->first(),
        ];

        $sizes = [
            'S' => Size::where('size_label', 'S')->first(),
            'M' => Size::where('size_label', 'M')->first(),
            'L' => Size::where('size_label', 'L')->first(),
        ];

        // Ambil user yang akan diberikan item
        $users = User::whereIn('email', [
            'dionadrew@gmail.com',
            'andi.wijaya@example.com',
            'putri.ayu@example.com',
            'budi.santoso@example.com',
            'sari.melati@example.com',
            'rizky.pratama@example.com',
        ])->get()->keyBy('email');

        $userItems = [
            'dionadrew@gmail.com' => [
                ['item' => 'Female Compression Top', 'size' => 'S', 'quantity' => 2],
                ['item' => 'Female Red Skirt', 'size' => 'M', 'quantity' => 1],
            ],
            'andi.wijaya@example.com' => [
                ['item' => 'Male White Shirt', 'size' => 'L', 'quantity' => 3],
            ],
            'putri.ayu@example.com' => [
                ['item' => 'Female Compression Top', 'size' => 'M', 'quantity' => 2],
                ['item' => 'Female Red Skirt', 'size' => 'S', 'quantity' => 1],
            ],
            'budi.santoso@example.com' => [
                ['item' => 'Male White Shirt', 'size' => 'M', 'quantity' => 1],
            ],
            'sari.melati@example.com' => [
                ['item' => 'Female Compression Top', 'size' => 'L', 'quantity' => 1],
            ],
            'rizky.pratama@example.com' => [
                ['item' => 'Female Red Skirt', 'size' => 'M', 'quantity' => 2],
            ],
        ];

        foreach ($userItems as $email => $itemsData) {
            $user = $users->get($email);

            if (!$user) continue;

            foreach ($itemsData as $data) {
                $item = $items[$data['item']] ?? null;
                $size = $sizes[$data['size']] ?? null;

                if ($item && $size) {
                    $itemVariant = ItemVariant::where('item_id', $item->id)
                        ->where('size_id', $size->id)
                        ->first();

                    if ($itemVariant) {
                        DB::table('user_item_sizes')->updateOrInsert(
                            [
                                'user_id' => $user->id,
                                'item_variant_id' => $itemVariant->id,
                            ],
                            [
                                'quantity' => $data['quantity'],
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );
                    }
                }
            }
        }
    }

    private function generateIdNumber(): string
    {
        $letters = strtoupper(Str::random(3));
        $numbers = str_pad((string)random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
        return $letters . $numbers;
    }
}
