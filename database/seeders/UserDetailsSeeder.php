<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserDetailsSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create sample users
        $users = [
            [
                'name' => 'Emily Wilson',
                'email' => 'emily.wilson@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'created_at' => Carbon::now()->subDays(30),
                'updated_at' => Carbon::now()->subDays(30),
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'michael.chen@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'created_at' => Carbon::now()->subDays(25),
                'updated_at' => Carbon::now()->subDays(25),
            ],
            [
                'name' => 'Sarah Davis',
                'email' => 'sarah.davis@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(15),
            ],
            [
                'name' => 'David Rodriguez',
                'email' => 'david.rodriguez@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(10),
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert($user);
        }

        // Create sample profiles
        $profiles = [
            [
                'email' => 'emily.wilson@example.com',
                'username' => 'emilywilson',
                'first_name' => 'Emily',
                'last_name' => 'Wilson',
                'phone' => '+1234567890',
                'date_of_birth' => Carbon::parse('1990-05-15'),
                'gender' => 'female',
                'location' => 'New York, USA',
                'bio' => 'Environmental enthusiast and waste reduction advocate.',
                'organization' => 'Green Earth Foundation',
                'website' => 'https://emilywilson.com',
                'social_links' => json_encode([
                    'facebook' => 'https://facebook.com/emilywilson',
                    'twitter' => 'https://twitter.com/emilywilson',
                    'linkedin' => 'https://linkedin.com/in/emilywilson'
                ]),
                'preferred_causes' => json_encode(['Environment', 'Climate Change', 'Sustainability']),
                'points_earned' => 150,
                'total_token' => 300,
                'achievements' => json_encode(['First Report', 'Eco Warrior', 'Monthly Champion']),
                'contribution' => 150,
                'waste_reports_count' => 25,
                'community_events_attended' => 8,
                'volunteer_hours' => 40,
                'carbon_footprint_saved' => 125.50,
                'notification_preferences' => json_encode(['email' => true, 'sms' => false]),
                'email_notifications' => true,
                'sms_notifications' => false,
                'profile_public' => true,
                'created_at' => Carbon::now()->subDays(30),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'email' => 'michael.chen@example.com',
                'username' => 'michaelchen',
                'first_name' => 'Michael',
                'last_name' => 'Chen',
                'phone' => '+1987654321',
                'date_of_birth' => Carbon::parse('1985-08-22'),
                'gender' => 'male',
                'location' => 'San Francisco, USA',
                'bio' => 'Zero waste lifestyle advocate and environmental consultant.',
                'organization' => 'EcoConsult Inc',
                'website' => 'https://michaelchen.eco',
                'social_links' => json_encode([
                    'instagram' => 'https://instagram.com/michaelchen',
                    'twitter' => 'https://twitter.com/michaelchen'
                ]),
                'preferred_causes' => json_encode(['Zero Waste', 'Plastic Reduction', 'Ocean Conservation']),
                'points_earned' => 220,
                'total_token' => 450,
                'achievements' => json_encode(['Zero Waste Hero', 'Ocean Saver', 'Top Contributor']),
                'contribution' => 220,
                'waste_reports_count' => 35,
                'community_events_attended' => 12,
                'volunteer_hours' => 60,
                'carbon_footprint_saved' => 198.75,
                'email_notifications' => true,
                'sms_notifications' => true,
                'profile_public' => true,
                'created_at' => Carbon::now()->subDays(25),
                'updated_at' => Carbon::now(),
            ],
            [
                'email' => 'sarah.davis@example.com',
                'username' => 'sarahdavis',
                'first_name' => 'Sarah',
                'last_name' => 'Davis',
                'phone' => '+1555123456',
                'date_of_birth' => Carbon::parse('1992-12-03'),
                'gender' => 'female',
                'location' => 'Chicago, USA',
                'bio' => 'Student passionate about environmental protection.',
                'preferred_causes' => json_encode(['Recycling', 'Education']),
                'points_earned' => 85,
                'total_token' => 170,
                'achievements' => json_encode(['First Timer', 'Student Advocate']),
                'contribution' => 85,
                'waste_reports_count' => 12,
                'community_events_attended' => 3,
                'volunteer_hours' => 15,
                'carbon_footprint_saved' => 45.25,
                'email_notifications' => true,
                'sms_notifications' => false,
                'profile_public' => false,
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(2),
            ],
        ];

        foreach ($profiles as $profile) {
            DB::table('profiles')->insert($profile);
        }

        // Create sample coin transactions
        $coinTransactions = [
            // Emily Wilson transactions
            ['user_email' => 'emily.wilson@example.com', 'reason' => 'Waste report submission', 'eco_coin_value' => 10, 'created_at' => Carbon::now()->subDays(25)],
            ['user_email' => 'emily.wilson@example.com', 'reason' => 'Community event participation', 'eco_coin_value' => 25, 'created_at' => Carbon::now()->subDays(20)],
            ['user_email' => 'emily.wilson@example.com', 'reason' => 'Product purchase: Eco Bag', 'eco_coin_value' => -50, 'created_at' => Carbon::now()->subDays(15)],
            ['user_email' => 'emily.wilson@example.com', 'reason' => 'Monthly bonus', 'eco_coin_value' => 50, 'created_at' => Carbon::now()->subDays(10)],
            ['user_email' => 'emily.wilson@example.com', 'reason' => 'Waste report submission', 'eco_coin_value' => 15, 'created_at' => Carbon::now()->subDays(5)],

            // Michael Chen transactions
            ['user_email' => 'michael.chen@example.com', 'reason' => 'Waste report submission', 'eco_coin_value' => 20, 'created_at' => Carbon::now()->subDays(23)],
            ['user_email' => 'michael.chen@example.com', 'reason' => 'Referral bonus', 'eco_coin_value' => 30, 'created_at' => Carbon::now()->subDays(18)],
            ['user_email' => 'michael.chen@example.com', 'reason' => 'Product purchase: Solar Charger', 'eco_coin_value' => -150, 'created_at' => Carbon::now()->subDays(12)],
            ['user_email' => 'michael.chen@example.com', 'reason' => 'Achievement unlock', 'eco_coin_value' => 75, 'created_at' => Carbon::now()->subDays(8)],

            // Sarah Davis transactions
            ['user_email' => 'sarah.davis@example.com', 'reason' => 'First waste report', 'eco_coin_value' => 15, 'created_at' => Carbon::now()->subDays(14)],
            ['user_email' => 'sarah.davis@example.com', 'reason' => 'Student bonus', 'eco_coin_value' => 20, 'created_at' => Carbon::now()->subDays(10)],
            ['user_email' => 'sarah.davis@example.com', 'reason' => 'Waste report submission', 'eco_coin_value' => 10, 'created_at' => Carbon::now()->subDays(6)],
        ];

        foreach ($coinTransactions as $transaction) {
            DB::table('coins')->insert(array_merge($transaction, ['updated_at' => $transaction['created_at']]));
        }

        // Create sample waste reports
        $wasteReports = [
            // Emily Wilson reports
            ['waste_type' => 'plastic', 'amount' => 2.5, 'unit' => 'kg', 'location' => 'Central Park', 'description' => 'Plastic bottles and containers found near lake', 'user_email' => 'emily.wilson@example.com', 'status' => 'verified', 'created_at' => Carbon::now()->subDays(25)],
            ['waste_type' => 'paper', 'amount' => 1.8, 'unit' => 'kg', 'location' => 'Office Building', 'description' => 'Recyclable paper waste from office cleanup', 'user_email' => 'emily.wilson@example.com', 'status' => 'verified', 'created_at' => Carbon::now()->subDays(20)],
            ['waste_type' => 'glass', 'amount' => 3.2, 'unit' => 'kg', 'location' => 'Residential Area', 'description' => 'Glass bottles from neighborhood cleanup', 'user_email' => 'emily.wilson@example.com', 'status' => 'verified', 'created_at' => Carbon::now()->subDays(15)],
            ['waste_type' => 'metal', 'amount' => 1.5, 'unit' => 'kg', 'location' => 'Industrial Zone', 'description' => 'Scrap metal collection', 'user_email' => 'emily.wilson@example.com', 'status' => 'pending', 'created_at' => Carbon::now()->subDays(5)],

            // Michael Chen reports
            ['waste_type' => 'plastic', 'amount' => 4.1, 'unit' => 'kg', 'location' => 'Beach Area', 'description' => 'Beach cleanup - plastic waste removal', 'user_email' => 'michael.chen@example.com', 'status' => 'verified', 'created_at' => Carbon::now()->subDays(23)],
            ['waste_type' => 'organic', 'amount' => 5.5, 'unit' => 'kg', 'location' => 'Community Garden', 'description' => 'Organic waste for composting', 'user_email' => 'michael.chen@example.com', 'status' => 'verified', 'created_at' => Carbon::now()->subDays(18)],
            ['waste_type' => 'electronic', 'amount' => 8.2, 'unit' => 'kg', 'location' => 'Tech Center', 'description' => 'E-waste collection drive', 'user_email' => 'michael.chen@example.com', 'status' => 'verified', 'created_at' => Carbon::now()->subDays(12)],

            // Sarah Davis reports
            ['waste_type' => 'paper', 'amount' => 0.8, 'unit' => 'kg', 'location' => 'University Campus', 'description' => 'Student paper waste collection', 'user_email' => 'sarah.davis@example.com', 'status' => 'verified', 'created_at' => Carbon::now()->subDays(14)],
            ['waste_type' => 'plastic', 'amount' => 1.2, 'unit' => 'kg', 'location' => 'Dormitory', 'description' => 'Plastic waste from student housing', 'user_email' => 'sarah.davis@example.com', 'status' => 'pending', 'created_at' => Carbon::now()->subDays(6)],
        ];

        foreach ($wasteReports as $report) {
            DB::table('wastereport')->insert(array_merge($report, ['updated_at' => $report['created_at']]));
        }

        // Create sample purchases
        $purchases = [
            [
                'email' => 'emily.wilson@example.com',
                'product_id' => 1, // Assuming product with ID 1 exists
                'name' => 'Emily Wilson',
                'address' => '123 Main St, New York, NY',
                'mobile' => '+1234567890',
                'eco_coins_spent' => 50,
                'status' => 'delivered',
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(13),
            ],
            [
                'email' => 'michael.chen@example.com',
                'product_id' => 2, // Assuming product with ID 2 exists
                'name' => 'Michael Chen',
                'address' => '456 Oak Ave, San Francisco, CA',
                'mobile' => '+1987654321',
                'eco_coins_spent' => 150,
                'status' => 'confirmed',
                'created_at' => Carbon::now()->subDays(12),
                'updated_at' => Carbon::now()->subDays(10),
            ],
        ];

        foreach ($purchases as $purchase) {
            DB::table('purchases')->insert($purchase);
        }

        $this->command->info('Sample user data seeded successfully!');
    }
}
