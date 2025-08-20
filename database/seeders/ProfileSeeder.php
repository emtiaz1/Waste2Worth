<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get existing user emails from logins table (since profiles references logins)
        $existingUsers = DB::table('logins')->pluck('email')->toArray();
        
        if (empty($existingUsers)) {
            $this->command->info('No users found in logins table. Please create users first.');
            return;
        }

        $sampleProfile = [
            'username' => 'ecochampion',
            'first_name' => 'Shah',
            'last_name' => 'Hasanta',
            'phone' => '+8801712345678',
            'date_of_birth' => '1990-05-15',
            'gender' => 'male',
            'location' => 'Dhaka, Bangladesh',
            'bio' => 'Environmental enthusiast and waste reduction advocate working towards a sustainable future.',
            'organization' => 'Waste2Worth Initiative',
            'website' => 'https://waste2worth.org',
            'social_links' => json_encode([
                'facebook' => 'https://facebook.com/shahhasanta',
                'twitter' => 'https://twitter.com/shahhasanta',
                'linkedin' => 'https://linkedin.com/in/shahhasanta'
            ]),
            'preferred_causes' => json_encode(['Environment', 'Climate Change', 'Sustainability', 'Waste Management']),
            'points_earned' => 250,
            'total_token' => 500,
            'achievements' => json_encode(['Platform Pioneer', 'Eco Warrior', 'Community Leader', 'Sustainability Champion']),
            'contribution' => 250,
            'waste_reports_count' => 45,
            'community_events_attended' => 15,
            'volunteer_hours' => 120,
            'carbon_footprint_saved' => 325.75,
            'email_notifications' => true,
            'sms_notifications' => true,
            'profile_public' => true,
        ];

        // Create profile for the existing user
        $email = $existingUsers[0];
        
        // Check if profile already exists
        if (!DB::table('profiles')->where('email', $email)->exists()) {
            $profile = array_merge($sampleProfile, [
                'email' => $email,
                'created_at' => Carbon::now()->subDays(30),
                'updated_at' => Carbon::now()->subDays(1),
            ]);
            
            DB::table('profiles')->insert($profile);
        }

        // Create sample coin transactions
        $coinTransactions = [
            [
                'user_email' => $email,
                'reason' => 'Platform registration bonus',
                'eco_coin_value' => 100,
                'created_at' => Carbon::now()->subDays(30),
                'updated_at' => Carbon::now()->subDays(30),
            ],
            [
                'user_email' => $email,
                'reason' => 'Profile completion bonus',
                'eco_coin_value' => 50,
                'created_at' => Carbon::now()->subDays(25),
                'updated_at' => Carbon::now()->subDays(25),
            ],
            [
                'user_email' => $email,
                'reason' => 'Waste report submission',
                'eco_coin_value' => 25,
                'created_at' => Carbon::now()->subDays(20),
                'updated_at' => Carbon::now()->subDays(20),
            ],
            [
                'user_email' => $email,
                'reason' => 'Community event participation',
                'eco_coin_value' => 75,
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(15),
            ],
            [
                'user_email' => $email,
                'reason' => 'Monthly achievement bonus',
                'eco_coin_value' => 100,
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(10),
            ],
            [
                'user_email' => $email,
                'reason' => 'Product purchase: Eco Bag',
                'eco_coin_value' => -80,
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(8),
            ],
            [
                'user_email' => $email,
                'reason' => 'Referral bonus',
                'eco_coin_value' => 50,
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'user_email' => $email,
                'reason' => 'Waste report verification',
                'eco_coin_value' => 30,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
        ];

        foreach ($coinTransactions as $transaction) {
            DB::table('coins')->insert($transaction);
        }

        $this->command->info('Sample profile and coin data created successfully for user: ' . $email);
    }
}
