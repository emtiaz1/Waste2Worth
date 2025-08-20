<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check profiles table
echo "Checking profiles for collector emails:" . PHP_EOL;
$collections = \App\Models\WasteCollection::select('collector_email')->distinct()->get();

foreach ($collections as $collection) {
    if ($collection->collector_email) {
        echo "Collector Email: " . $collection->collector_email . PHP_EOL;
        
        $profile = \App\Models\Profile::where('email', $collection->collector_email)->first();
        if ($profile) {
            echo "  - Profile found: " . $profile->first_name . " " . $profile->last_name . " (" . $profile->username . ")" . PHP_EOL;
            echo "  - Phone: " . ($profile->phone ?: 'N/A') . PHP_EOL;
        } else {
            echo "  - No profile found" . PHP_EOL;
        }
        
        // Check if there's a login record
        $login = \App\Models\Login::where('email', $collection->collector_email)->first();
        if ($login) {
            echo "  - Login record found: " . $login->email . PHP_EOL;
        } else {
            echo "  - No login record found" . PHP_EOL;
        }
        echo PHP_EOL;
    }
}
