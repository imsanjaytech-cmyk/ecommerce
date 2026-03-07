<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ProductImagesSeeder extends Seeder
{
    public function run(): void
    {
        $images = [

            [
                'product_id' => 1,
                'image' => 'products/gallery/chocolate1.jpg'
            ],
            [
                'product_id' => 1,
                'image' => 'products/gallery/chocolate2.jpg'
            ],
            [
                'product_id' => 2,
                'image' => 'products/gallery/teddy1.jpg'
            ],
            [
                'product_id' => 2,
                'image' => 'products/gallery/teddy2.jpg'
            ],
            [
                'product_id' => 3,
                'image' => 'products/gallery/birthday1.jpg'
            ],
            [
                'product_id' => 3,
                'image' => 'products/gallery/birthday2.jpg'
            ]

        ];

        foreach ($images as $image) {

            DB::table('product_images')->insert([
                'product_id' => $image['product_id'],
                'path' => $image['image'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

        }
    }
}