<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'email',
        'username',
        'first_name',
        'last_name',
        'phone',
        'date_of_birth',
        'gender',
        'profile_picture',
        'location',
        'status',
        'bio',
        'organization',
        'website',
        'social_links',
        'interests',
        'skills',
        'achievements',
        'contribution',
        'total_token',
        'points_earned',
        'waste_reports_count',
        'community_events_attended',
        'volunteer_hours',
        'carbon_footprint_saved',
        'token_usages',
        'preferred_causes',
        'notification_preferences',
        'email_notifications',
        'sms_notifications',
        'profile_public'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'token_usages' => 'array',
        'social_links' => 'array',
        'interests' => 'array',
        'skills' => 'array',
        'preferred_causes' => 'array',
        'date_of_birth' => 'date',
        'email_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
        'profile_public' => 'boolean',
        'carbon_footprint_saved' => 'decimal:2'
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get the display name (full name or username).
     */
    public function getDisplayNameAttribute()
    {
        return $this->full_name ?: $this->username;
    }
}
