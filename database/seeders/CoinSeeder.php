<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coin;
use Carbon\Carbon;

class CoinSeeder extends Seeder
{
    public function run()
    {
        $sampleData = [
            [
                'user_email' => 'test@example.com',
                'reason' => 'Beach Cleanup Event',
                'eco_coin_value' => 150,
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'user_email' => 'test@example.com',
                'reason' => 'Waste Report Verified',
                'eco_coin_value' => 75,
                'created_at' => Carbon::now()->subDays(1),
            ],
            [
                'user_email' => 'test@example.com',
                'reason' => 'Monthly Challenge',
                'eco_coin_value' => 200,
                'created_at' => Carbon::now(),
            ],
            [
                'user_email' => 'user@example.com',
                'reason' => 'Recycling Initiative',
                'eco_coin_value' => 100,
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'user_email' => 'user@example.com',
                'reason' => 'Community Event Participation',
                'eco_coin_value' => 125,
                'created_at' => Carbon::now()->subDays(1),
            ],
        ];

        foreach ($sampleData as $data) {
            Coin::create($data);
        }
    }
}
