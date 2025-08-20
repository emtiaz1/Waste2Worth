<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProductsAndPurchasesSeeder::class,
        ]);
        \App\Models\Event::truncate();
        \App\Models\Event::insert([
            [
                'name' => 'Sher-e-Bangla Park Cleanup',
                'location' => 'Sher-e-Bangla Nagar Park, Agargaon',
                'date' => '2025-08-15',
                'time' => '8:00 AM - 12:00 PM',
                'description' => 'Join us to restore the natural beauty of Sher-e-Bangla Park. Gloves and bags provided.',
                'image' => 'frontend/image/clean1.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Cox's Bazar Beach Cleanup",
                'location' => "Cox's Bazar Sea Beach, Laboni Point",
                'date' => '2025-08-20',
                'time' => '6:00 AM - 10:00 AM',
                'description' => "Help protect marine life by removing litter from Cox's Bazar beach. Volunteers welcome!",
                'image' => 'frontend/image/clean2.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mirpur-1 Street Cleaning',
                'location' => 'Mirpur-1, Section-2, Main Road',
                'date' => '2025-08-25',
                'time' => '7:00 AM - 11:00 AM',
                'description' => 'Make our city cleaner! Join others in a fun and impactful community effort in Mirpur.',
                'image' => 'frontend/image/clean3.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Turag River Bank Cleanup',
                'location' => 'Turag River, Uttara Sector-18',
                'date' => '2025-08-30',
                'time' => '7:30 AM - 11:30 AM',
                'description' => 'Support local ecology by cleaning up along the Turag riverbank trail. All ages welcome.',
                'image' => 'frontend/image/clean4.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
