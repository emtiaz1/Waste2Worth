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
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('username');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('phone')->nullable()->after('email');
            $table->date('date_of_birth')->nullable()->after('last_name');
            $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say'])->nullable()->after('date_of_birth');
            $table->text('bio')->nullable()->after('status');
            $table->string('organization')->nullable()->after('bio');
            $table->string('website')->nullable()->after('organization');
            $table->json('social_links')->nullable()->after('website');
            $table->json('interests')->nullable()->after('social_links');
            $table->json('skills')->nullable()->after('interests');
            $table->enum('notification_preferences', ['all', 'important', 'none'])->default('all')->after('skills');
            $table->boolean('email_notifications')->default(true)->after('notification_preferences');
            $table->boolean('sms_notifications')->default(false)->after('email_notifications');
            $table->boolean('profile_public')->default(true)->after('sms_notifications');
            $table->integer('points_earned')->default(0)->after('total_token');
            $table->integer('waste_reports_count')->default(0)->after('points_earned');
            $table->integer('community_events_attended')->default(0)->after('waste_reports_count');
            $table->integer('volunteer_hours')->default(0)->after('community_events_attended');
            $table->decimal('carbon_footprint_saved', 8, 2)->default(0)->after('volunteer_hours');
            $table->json('preferred_causes')->nullable()->after('carbon_footprint_saved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'phone',
                'date_of_birth',
                'gender',
                'bio',
                'organization',
                'website',
                'social_links',
                'interests',
                'skills',
                'notification_preferences',
                'email_notifications',
                'sms_notifications',
                'profile_public',
                'points_earned',
                'waste_reports_count',
                'community_events_attended',
                'volunteer_hours',
                'carbon_footprint_saved',
                'preferred_causes'
            ]);
        });
    }
};
