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
        Schema::create('waste_collections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('waste_report_id'); // Link to wastereport table
            $table->string('requester_email'); // User who requested collection
            $table->string('status')->default('pending'); // pending, assigned, collected, completed
            $table->string('collector_name')->nullable(); // Name of assigned collector
            $table->string('collector_contact')->nullable(); // Contact info for collector
            $table->timestamp('requested_at');
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('collected_at')->nullable();
            $table->text('collection_notes')->nullable();
            $table->decimal('estimated_weight', 8, 2)->nullable(); // Estimated collection weight
            $table->decimal('actual_weight', 8, 2)->nullable(); // Actual collected weight
            $table->json('collection_photos')->nullable(); // Photos of collection
            $table->timestamps();
            
            $table->foreign('waste_report_id')->references('id')->on('wastereport')->onDelete('cascade');
            $table->index('requester_email');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waste_collections');
    }
};
