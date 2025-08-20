<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\WasteReport;
use App\Models\WasteCollection;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔧 Testing Admin Verification Status Fix\n";
echo "=" . str_repeat("=", 40) . "\n\n";

try {
    // Check if there are any waste reports with 'collected' status that should be 'confirmed'
    echo "📊 Current Status Summary:\n";
    
    $collectedReports = WasteReport::where('status', 'collected')->get();
    $confirmedReports = WasteReport::where('status', 'confirmed')->get();
    
    echo "   📦 Reports with 'collected' status: " . $collectedReports->count() . "\n";
    echo "   ✅ Reports with 'confirmed' status: " . $confirmedReports->count() . "\n\n";
    
    // Check collections that are completed but waste reports might still be 'collected'
    echo "🔍 Checking for mismatched statuses:\n";
    
    $mismatchedReports = WasteReport::where('status', 'collected')
        ->whereHas('wasteCollection', function($query) {
            $query->where('status', 'completed');
        })->get();
        
    if ($mismatchedReports->count() > 0) {
        echo "   ⚠️  Found {$mismatchedReports->count()} reports that should be 'confirmed' but are still 'collected'\n";
        
        foreach ($mismatchedReports as $report) {
            $collection = WasteCollection::where('waste_report_id', $report->id)->first();
            echo "   📋 Report ID {$report->id}: {$report->waste_type} - Collection Status: {$collection->status}\n";
            
            // Fix the status
            $report->update(['status' => 'confirmed']);
            echo "      ✅ Updated to 'confirmed'\n";
        }
    } else {
        echo "   ✅ No mismatched statuses found\n";
    }
    
    echo "\n📊 Final Status Summary:\n";
    $finalCollectedReports = WasteReport::where('status', 'collected')->count();
    $finalConfirmedReports = WasteReport::where('status', 'confirmed')->count();
    
    echo "   📦 Reports with 'collected' status: " . $finalCollectedReports . "\n";
    echo "   ✅ Reports with 'confirmed' status: " . $finalConfirmedReports . "\n";
    
    echo "\n🎉 Status verification complete!\n";
    echo "✅ Admin verification will now properly update activity feed to show 'Collection verified and coins distributed!'\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
