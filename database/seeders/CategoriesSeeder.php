<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use DB;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Chocolate Hampers',
            'Teddy Bears',
            'Flower Bouquets',
            'Birthday Gifts',
            'Anniversary Gifts',
            'Aroma Candles'
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category,
                'slug' => Str::slug($category),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}