<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductsAndPurchasesSeeder extends Seeder
{
    public function run()
    {
        // Insert products
        DB::table('products')->insert([
            [
                'name' => 'Eco-Friendly Water Bottle',
                'image' => 'frontend/image/products/water-bottle.jpg',
                'stock' => 50,
                'eco_coin_value' => 100,
                'description' => 'Reusable stainless steel water bottle, perfect for daily use',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Bamboo Utensil Set',
                'image' => 'frontend/image/products/bamboo-utensils.jpg',
                'stock' => 30,
                'eco_coin_value' => 75,
                'description' => 'Sustainable bamboo cutlery set with carrying case',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Recycled Notebook',
                'image' => 'frontend/image/products/notebook.jpg',
                'stock' => 100,
                'eco_coin_value' => 50,
                'description' => 'Notebook made from 100% recycled paper',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Canvas Shopping Bag',
                'image' => 'frontend/image/products/canvas-bag.jpg',
                'stock' => 80,
                'eco_coin_value' => 60,
                'description' => 'Durable canvas shopping bag, say no to plastic',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Solar-Powered Charger',
                'image' => 'frontend/image/products/solar-charger.jpg',
                'stock' => 25,
                'eco_coin_value' => 200,
                'description' => 'Portable solar power bank for eco-friendly charging',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Bamboo Toothbrush Set',
                'image' => 'frontend/image/products/bamboo-utensils.jpg',
                'stock' => 150,
                'eco_coin_value' => 40,
                'description' => 'Pack of 4 biodegradable bamboo toothbrushes',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Compost Bin',
                'image' => 'frontend/image/products/compost-bin.jpg',
                'stock' => 20,
                'eco_coin_value' => 150,
                'description' => 'Kitchen compost bin with charcoal filter',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Metal Straw Set',
                'image' => 'frontend/image/products/metal-straws.jpg',
                'stock' => 200,
                'eco_coin_value' => 45,
                'description' => 'Set of 4 reusable metal straws with cleaning brush',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // You can uncomment this section once you have users in your system
        /*
        // Insert sample purchases
        DB::table('purchases')->insert([
            [
                'email' => 'user@example.com',
                'product_id' => 1,
                'name' => 'John Doe',
                'address' => '123 Green Street',
                'mobile' => '+8801712345678',
                'eco_coins_spent' => 100,
                'status' => 'delivered',
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'email' => 'user@example.com',
                'product_id' => 3,
                'name' => 'John Doe',
                'address' => '123 Green Street',
                'mobile' => '+8801712345678',
                'eco_coins_spent' => 50,
                'status' => 'delivered',
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'email' => 'jane@example.com',
                'product_id' => 2,
                'name' => 'Jane Smith',
                'address' => '456 Eco Avenue',
                'mobile' => '+8801787654321',
                'eco_coins_spent' => 75,
                'status' => 'processing',
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
        ]);
        */
    }
}
