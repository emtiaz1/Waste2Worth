<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix any double-encoded JSON fields in the profiles table
        $profiles = DB::table('profiles')->whereNotNull('social_links')->get();
        
        foreach ($profiles as $profile) {
            $updates = [];
            
            // Fix social_links if it's double-encoded
            if ($profile->social_links) {
                $decoded = json_decode($profile->social_links, true);
                if (is_string($decoded)) {
                    $updates['social_links'] = $decoded;
                }
            }
            
            // Fix interests if it's double-encoded
            if ($profile->interests) {
                $decoded = json_decode($profile->interests, true);
                if (is_string($decoded)) {
                    $updates['interests'] = $decoded;
                }
            }
            
            // Fix skills if it's double-encoded
            if ($profile->skills) {
                $decoded = json_decode($profile->skills, true);
                if (is_string($decoded)) {
                    $updates['skills'] = $decoded;
                }
            }
            
            // Fix preferred_causes if it's double-encoded
            if ($profile->preferred_causes) {
                $decoded = json_decode($profile->preferred_causes, true);
                if (is_string($decoded)) {
                    $updates['preferred_causes'] = $decoded;
                }
            }
            
            if (!empty($updates)) {
                DB::table('profiles')->where('id', $profile->id)->update($updates);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot reverse this migration as we're fixing corrupted data
    }
};
