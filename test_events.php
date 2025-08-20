<?php

require_once 'vendor/autoload.php';

use App\Models\Event;
use App\Models\EventRegistration;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🎪 Creating Test Events for Dashboard\n";
echo "=" . str_repeat("=", 40) . "\n\n";

try {
    // Create sample events
    $events = [
        [
            'name' => 'Community Beach Cleanup',
            'date' => now()->addDays(3)->toDateString(),
            'time' => '09:00:00',
            'location' => 'Sunrise Beach Park',
            'description' => 'Join us for a community beach cleanup event. Bring gloves and water bottles!',
            'image' => null,
        ],
        [
            'name' => 'Recycling Workshop',
            'date' => now()->addWeek()->toDateString(),
            'time' => '14:00:00', 
            'location' => 'Community Center Hall A',
            'description' => 'Learn creative ways to recycle everyday items into useful products.',
            'image' => null,
        ],
        [
            'name' => 'Eco-Friendly Garden Tour',
            'date' => now()->addDays(10)->toDateString(),
            'time' => '10:30:00',
            'location' => 'Green Gardens District',
            'description' => 'Tour sustainable gardens and learn about organic farming practices.',
            'image' => null,
        ],
        [
            'name' => 'Zero Waste Lifestyle Seminar',
            'date' => now()->addDays(15)->toDateString(),
            'time' => '16:00:00',
            'location' => 'Environmental Education Center',
            'description' => 'Discover practical tips for reducing waste in your daily life.',
            'image' => null,
        ],
        [
            'name' => 'Plastic-Free Challenge Kickoff',
            'date' => now()->addDays(21)->toDateString(),
            'time' => '11:00:00',
            'location' => 'City Plaza Main Stage',
            'description' => 'Start your plastic-free journey with our 30-day challenge.',
            'image' => null,
        ],
    ];
    
    foreach ($events as $eventData) {
        $event = Event::create($eventData);
        
        $participantCount = rand(12, 35);
        echo "✅ Created: {$eventData['name']}\n";
        echo "   📅 Date: {$eventData['date']} at {$eventData['time']}\n";
        echo "   📍 Location: {$eventData['location']}\n";
        echo "   👥 Estimated Participants: {$participantCount}\n\n";
    }
    
    echo "🎉 Test events created successfully!\n";
    echo "📊 Total events: " . Event::count() . "\n";
    echo "📅 Upcoming events: " . Event::where('date', '>=', now()->toDateString())->count() . "\n\n";
    
    echo "✅ The upcoming events section on the dashboard should now display real data!\n";
    echo "💡 Events are automatically sorted by date and time.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
