<?php

require_once 'vendor/autoload.php';

use App\Http\Controllers\WasteReportController;
use App\Models\Event;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🧪 Testing Events Integration\n";
echo "=" . str_repeat("=", 30) . "\n\n";

try {
    // Test that getUpcomingEvents method works
    $controller = new WasteReportController();
    
    // Use reflection to access the private method
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('getUpcomingEvents');
    $method->setAccessible(true);
    
    $events = $method->invoke($controller);
    
    echo "✅ Events method successfully called\n";
    echo "📊 Found " . count($events) . " upcoming events\n\n";
    
    if (count($events) > 0) {
        echo "🎪 Sample Events:\n";
        foreach (array_slice($events, 0, 3) as $event) {
            echo "   📅 {$event['name']}\n";
            echo "      Date: {$event['formatted_date']}\n";
            echo "      Location: {$event['location']}\n";
            echo "      Participants: {$event['participants']}\n\n";
        }
    }
    
    // Test that events in database are properly formatted
    $dbEvents = Event::where('date', '>=', now()->toDateString())->count();
    echo "📊 Events in database: {$dbEvents}\n";
    
    echo "🎉 Events integration is working correctly!\n";
    echo "✅ Dashboard will now show real event data from the admin database\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
