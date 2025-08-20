<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\WasteReport;
use App\Models\WasteCollection;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Testing AdminController Statistics Logic ===\n\n";

// Create test data with different statuses
echo "Creating test data...\n";

// Clear existing data properly with foreign key constraints
\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
WasteCollection::truncate();
WasteReport::truncate();
\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

// Create pending waste report (no collection)
$pendingReport = WasteReport::create([
    'user_email' => 'user1@example.com',
    'waste_type' => 'plastic',
    'amount' => '3',
    'unit' => 'kg',
    'location' => 'Location 1',
    'description' => 'Pending report',
    'status' => 'pending'
]);

// Create assigned collection
$assignedReport = WasteReport::create([
    'user_email' => 'user2@example.com',
    'waste_type' => 'metal',
    'amount' => '2',
    'unit' => 'kg',
    'location' => 'Location 2',
    'description' => 'Assigned report',
    'status' => 'pending'
]);

$assignedCollection = WasteCollection::create([
    'waste_report_id' => $assignedReport->id,
    'requester_email' => 'collector1@example.com',
    'estimated_weight' => 2.0,
    'status' => 'assigned'
]);

// Create collected (ready for review)
$collectedReport = WasteReport::create([
    'user_email' => 'user3@example.com',
    'waste_type' => 'paper',
    'amount' => '1',
    'unit' => 'kg',
    'location' => 'Location 3',
    'description' => 'Collected report',
    'status' => 'pending'
]);

$collectedCollection = WasteCollection::create([
    'waste_report_id' => $collectedReport->id,
    'requester_email' => 'collector2@example.com',
    'estimated_weight' => 1.0,
    'actual_weight' => 1.5,
    'status' => 'collected'
]);

// Create confirmed/completed
$confirmedReport = WasteReport::create([
    'user_email' => 'user4@example.com',
    'waste_type' => 'glass',
    'amount' => '4',
    'unit' => 'kg',
    'location' => 'Location 4',
    'description' => 'Confirmed report',
    'status' => 'confirmed'
]);

$completedCollection = WasteCollection::create([
    'waste_report_id' => $confirmedReport->id,
    'requester_email' => 'collector3@example.com',
    'estimated_weight' => 4.0,
    'actual_weight' => 4.2,
    'status' => 'completed'
]);

echo "Test data created successfully!\n\n";

// Now test the AdminController logic
echo "Testing statistics calculation...\n";

// Get all waste reports with their collection details
$wasteReports = WasteReport::with(['collections' => function($query) {
    $query->with('collectorProfile')->orderBy('created_at', 'desc');
}])
->orderBy('created_at', 'desc')
->get();

// Transform data like the controller does
$reportsData = $wasteReports->map(function($report) {
    $latestCollection = $report->collections->first();
    
    $collectionData = null;
    if ($latestCollection) {
        $collectionData = [
            'id' => $latestCollection->id,
            'status' => $latestCollection->status,
            'actual_weight' => $latestCollection->actual_weight,
        ];
    }
    
    return [
        'report_id' => $report->id,
        'waste_type' => $report->waste_type,
        'status' => $report->status ?? 'pending',
        'collection' => $collectionData
    ];
});

// Calculate statistics like the controller
$statistics = [
    'pending' => $reportsData->filter(function($report) {
        return $report['status'] === 'pending';
    })->count(),
    'assigned' => $reportsData->filter(function($report) {
        return isset($report['collection']['status']) && $report['collection']['status'] === 'assigned';
    })->count(),
    'ready_for_review' => $reportsData->filter(function($report) {
        return isset($report['collection']['status']) && 
               in_array($report['collection']['status'], ['submitted', 'collected']);
    })->count(),
    'confirmed' => $reportsData->filter(function($report) {
        return $report['status'] === 'confirmed' ||
               (isset($report['collection']['status']) && 
                in_array($report['collection']['status'], ['confirmed', 'completed']));
    })->count(),
];

echo "Statistics Results:\n";
echo "- Pending: {$statistics['pending']}\n";
echo "- Assigned: {$statistics['assigned']}\n"; 
echo "- Ready for Review: {$statistics['ready_for_review']}\n";
echo "- Confirmed: {$statistics['confirmed']}\n\n";

echo "Expected Results:\n";
echo "- Pending: 1 (1 report with no collection)\n";
echo "- Assigned: 1 (1 collection with 'assigned' status)\n";
echo "- Ready for Review: 1 (1 collection with 'collected' status)\n";
echo "- Confirmed: 1 (1 report with 'confirmed' status + 'completed' collection)\n\n";

echo "=== Test Complete ===\n";
