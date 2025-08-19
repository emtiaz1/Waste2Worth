<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'Reusable Water Bottle',
                'description' => 'Eco-friendly stainless steel bottle',
                'eco_coin_price' => 500,
                'stock' => 50,
                'image' => 'frontend/image/bottle.jpg',
                'is_active' => true
            ],
            [
                'name' => 'Organic Tote Bag',
                'description' => '100% organic cotton bag',
                'eco_coin_price' => 300,
                'stock' => 75,
                'image' => 'frontend/image/bag.jpg',
                'is_active' => true
            ],
            [
                'name' => 'Plant a Tree',
                'description' => 'Plant a tree in your name',
                'eco_coin_price' => 1000,
                'stock' => 100,
                'image' => 'frontend/image/plant.jpg',
                'is_active' => true
            ],
            [
                'name' => 'Solar Power Bank',
                'description' => 'Portable solar charging device',
                'eco_coin_price' => 800,
                'stock' => 25,
                'image' => 'frontend/image/pb.jpg',
                'is_active' => true
            ],
            [
                'name' => 'Eco Cleaning Kit',
                'description' => 'Natural cleaning products set',
                'eco_coin_price' => 450,
                'stock' => 40,
                'image' => 'frontend/image/kit.jpg',
                'is_active' => true
            ],
            [
                'name' => 'Green Store Voucher',
                'description' => '20% off at partner eco stores',
                'eco_coin_price' => 600,
                'stock' => 200,
                'image' => 'frontend/image/voucher.jpg',
                'is_active' => true
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
