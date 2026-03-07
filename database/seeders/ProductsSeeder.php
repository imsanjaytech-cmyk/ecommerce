<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use DB;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {

        $products = [

            [
                'name' => 'Premium Chocolate Hamper',
                'category_id' => 1,
                'price' => 1299,
                'thumbnail' => 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c'
            ],

            [
                'name' => 'Romantic Teddy Bear',
                'category_id' => 2,
                'price' => 799,
                'thumbnail' => 'https://images.unsplash.com/photo-1596464716127-f2a82984de30'
            ],

            [
                'name' => 'Birthday Surprise Gift Box',
                'category_id' => 4,
                'price' => 999,
                'thumbnail' => 'https://images.unsplash.com/photo-1607082349566-187342175e2f'
            ],

            [
                'name' => 'Luxury Aroma Candle Set',
                'category_id' => 6,
                'price' => 599,
                'thumbnail' => 'https://images.unsplash.com/photo-1603006905003-be475563bc59'
            ],

            [
                'name' => 'Anniversary Couple Gift Hamper',
                'category_id' => 5,
                'price' => 1499,
                'thumbnail' => 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48'
            ]

        ];

        foreach ($products as $product) {

            DB::table('products')->insert([
                'name' => $product['name'],
                'slug' => Str::slug($product['name']),
                'short_description' => 'Beautiful premium gift item perfect for special occasions.',
                'description' => 'High quality fancy gift item perfect for birthdays, anniversaries and celebrations.',
                'sku' => 'SKU-' . rand(1000, 9999),
                'regular_price' => $product['price'],
                'sale_price' => $product['price'] - 100,
                'stock_quantity' => 50,
                'manage_stock' => 1,
                'stock_status' => 'in_stock',
                'category_id' => $product['category_id'],
                'thumbnail' => $product['thumbnail'],
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
