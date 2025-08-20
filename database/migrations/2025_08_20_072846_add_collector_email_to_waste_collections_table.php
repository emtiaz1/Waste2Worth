<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('waste_collections', function (Blueprint $table) {
            $table->string('collector_email')->nullable()->after('requester_email');
            $table->decimal('expected_weight', 8, 2)->nullable()->after('estimated_weight');
            $table->date('assigned_date')->nullable()->after('assigned_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('waste_collections', function (Blueprint $table) {
            $table->dropColumn(['collector_email', 'expected_weight', 'assigned_date']);
        });
    }
};
