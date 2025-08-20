<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\WasteReport;
use App\Models\WasteCollection;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Testing Admin Statistics ===\n\n";

// Check total waste reports
$totalReports = WasteReport::count();
echo "Total Waste Reports: {$totalReports}\n\n";

if ($totalReports > 0) {
    // Check reports by status
    $pending = WasteReport::where('status', 'pending')->count();
    $confirmed = WasteReport::where('status', 'confirmed')->count();
    
    echo "Reports by Status:\n";
    echo "- Pending: {$pending}\n";
    echo "- Confirmed: {$confirmed}\n\n";
    
    // Check collections
    $totalCollections = WasteCollection::count();
    echo "Total Collections: {$totalCollections}\n\n";
    
    if ($totalCollections > 0) {
        $assigned = WasteCollection::where('status', 'assigned')->count();
        $collected = WasteCollection::where('status', 'collected')->count();
        $submitted = WasteCollection::where('status', 'submitted')->count();
        $completed = WasteCollection::where('status', 'completed')->count();
        
        echo "Collections by Status:\n";
        echo "- Assigned: {$assigned}\n";
        echo "- Collected: {$collected}\n";
        echo "- Submitted: {$submitted}\n";
        echo "- Completed: {$completed}\n\n";
    }
    
    // Show sample data
    echo "Sample Reports with Collections:\n";
    $reports = WasteReport::with('collections')->take(5)->get();
    foreach ($reports as $report) {
        echo "Report ID: {$report->id}, Status: {$report->status}\n";
        if ($report->collections->count() > 0) {
            foreach ($report->collections as $collection) {
                echo "  - Collection ID: {$collection->id}, Status: {$collection->status}\n";
            }
        } else {
            echo "  - No collections\n";
        }
        echo "\n";
    }
} else {
    echo "No waste reports found in database.\n";
    echo "Let's create some test data...\n\n";
    
    // Create test waste report
    $report = WasteReport::create([
        'user_email' => 'test@example.com',
        'waste_type' => 'plastic',
        'amount' => '5kg',
        'location' => 'Test Location',
        'description' => 'Test waste report',
        'status' => 'pending'
    ]);
    echo "Created test report ID: {$report->id}\n";
    
    // Create test collection
    $collection = WasteCollection::create([
        'waste_report_id' => $report->id,
        'requester_email' => 'collector@example.com',
        'estimated_weight' => 5.0,
        'status' => 'assigned'
    ]);
    echo "Created test collection ID: {$collection->id}\n";
    
    echo "\nTest data created successfully!\n";
}

echo "\n=== Test Complete ===\n";
