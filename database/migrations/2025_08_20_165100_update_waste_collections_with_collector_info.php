<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\WasteCollection;
use App\Models\Profile;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing waste collections that don't have collector names
        $collections = WasteCollection::whereNull('collector_name')
            ->orWhere('collector_name', '')
            ->get();

        foreach ($collections as $collection) {
            if ($collection->collector_email) {
                $profile = Profile::where('email', $collection->collector_email)->first();
                
                $collectorName = 'Unknown User';
                $collectorContact = null;
                
                if ($profile) {
                    $collectorName = trim($profile->first_name . ' ' . $profile->last_name);
                    $collectorContact = $profile->phone;
                    
                    if (!$collectorName || $collectorName === ' ') {
                        $collectorName = $profile->username ?: 'Unknown User';
                    }
                }
                
                $collection->update([
                    'collector_name' => $collectorName,
                    'collector_contact' => $collectorContact
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this data migration
    }
};
