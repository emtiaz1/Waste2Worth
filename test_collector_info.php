<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test collector information retrieval
$collection = \App\Models\WasteCollection::with('collectorProfile')->first();
if ($collection) {
    echo 'Collection ID: ' . $collection->id . PHP_EOL;
    echo 'Collector Email: ' . $collection->collector_email . PHP_EOL;
    echo 'Collector Name: ' . $collection->collector_name . PHP_EOL;
    echo 'Collector Contact: ' . ($collection->collector_contact ?: 'N/A') . PHP_EOL;
    if ($collection->collectorProfile) {
        echo 'Profile Name: ' . $collection->collectorProfile->first_name . ' ' . $collection->collectorProfile->last_name . PHP_EOL;
        echo 'Profile Phone: ' . ($collection->collectorProfile->phone ?: 'N/A') . PHP_EOL;
    } else {
        echo 'No profile found for collector' . PHP_EOL;
    }
} else {
    echo 'No collections found' . PHP_EOL;
}

// Test admin view data
echo PHP_EOL . "Testing admin view data:" . PHP_EOL;
$wasteReports = \App\Models\WasteReport::with(['collections' => function($query) {
    $query->with('collectorProfile')->orderBy('created_at', 'desc');
}])->first();

if ($wasteReports) {
    $latestCollection = $wasteReports->collections->first();
    if ($latestCollection) {
        echo 'Admin view collector name: ' . ($latestCollection->collector_name ?: 
            ($latestCollection->collectorProfile ? 
                trim($latestCollection->collectorProfile->first_name . ' ' . $latestCollection->collectorProfile->last_name) : 
                'Unknown User')) . PHP_EOL;
    }
}
