<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // First ensure we have a waste report
        $wasteReportExists = DB::table('wastereport')->count() > 0;
        
        if (!$wasteReportExists) {
            DB::table('wastereport')->insert([
                'waste_type' => 'Plastic',
                'amount' => 15.5,
                'unit' => 'kg',
                'location' => 'Main Street Park',
                'description' => 'Plastic bottles and containers',
                'user_email' => 'user1@example.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
        
        // Add sample waste collection requests
        DB::table('waste_collections')->insert([
            [
                'waste_report_id' => 1,
                'requester_email' => 'user1@example.com',
                'status' => 'pending',
                'requested_at' => Carbon::now()->subHours(2),
                'estimated_weight' => 15.5,
                'collection_notes' => 'Large amount of plastic waste needs pickup',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
        
        // Add some eco coins
        DB::table('coins')->insert([
            [
                'user_email' => 'user1@example.com',
                'reason' => 'Waste reporting bonus',
                'eco_coin_value' => 50,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
        
        echo "Test data seeded successfully!\n";
    }
}

// Run the seeder
$seeder = new TestDataSeeder();
$seeder->run();
