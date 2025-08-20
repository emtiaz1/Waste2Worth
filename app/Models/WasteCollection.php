<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WasteCollection extends Model
{
    protected $fillable = [
        'waste_report_id',
        'requester_email',
        'collector_email', // New field for collector
        'status',
        'collector_name',
        'collector_contact',
        'requested_at',
        'assigned_at',
        'assigned_date', // New field
        'collected_at',
        'collection_notes',
        'estimated_weight',
        'expected_weight', // New field
        'actual_weight',
        'collection_photos'
    ];

    protected $casts = [
        'collection_photos' => 'array',
        'requested_at' => 'datetime',
        'assigned_at' => 'datetime',
        'collected_at' => 'datetime'
    ];

    /**
     * Get the waste report associated with this collection
     */
    public function wasteReport()
    {
        return $this->belongsTo(WasteReport::class, 'waste_report_id');
    }

    /**
     * Get the collector profile information
     */
    public function collectorProfile()
    {
        return $this->belongsTo(Profile::class, 'collector_email', 'email');
    }

    /**
     * Get the requester profile information
     */
    public function requesterProfile()
    {
        return $this->belongsTo(Profile::class, 'requester_email', 'email');
    }

    /**
     * Get the collector name with fallback to profile data
     */
    public function getCollectorNameAttribute($value)
    {
        if ($value) {
            return $value;
        }

        // Fallback to profile data if collector_name is not set
        if ($this->collector_email && $this->collectorProfile) {
            $profile = $this->collectorProfile;
            
            // Try to get full name first
            $firstName = trim($profile->first_name ?? '');
            $lastName = trim($profile->last_name ?? '');
            
            if ($firstName || $lastName) {
                return trim($firstName . ' ' . $lastName);
            } else {
                // Fall back to username
                return $profile->username ?: 'Unknown User';
            }
        }

        return 'Unknown User';
    }

    /**
     * Get the collector contact with fallback to profile data
     */
    public function getCollectorContactAttribute($value)
    {
        if ($value) {
            return $value;
        }

        // Fallback to profile data if collector_contact is not set
        if ($this->collector_email && $this->collectorProfile) {
            return $this->collectorProfile->phone;
        }

        return null;
    }

    /**
     * Get pending collections for dashboard
     */
    public static function getPendingCollections($limit = 5)
    {
        return self::with('wasteReport')
            ->where('status', 'pending')
            ->orderBy('requested_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get collection statistics
     */
    public static function getCollectionStats()
    {
        return [
            'pending' => self::where('status', 'pending')->count(),
            'assigned' => self::where('status', 'assigned')->count(),
            'submitted' => self::where('status', 'submitted')->count(),
            'collected' => self::whereIn('status', ['collected', 'confirmed'])->count(),
            'completed' => self::whereIn('status', ['completed', 'confirmed'])->count(),
            'total_weight_collected' => self::whereIn('status', ['completed', 'confirmed', 'collected'])
                ->sum('actual_weight'),
            'today_collections' => self::whereDate('updated_at', today())
                ->whereIn('status', ['completed', 'confirmed', 'collected'])
                ->count()
        ];
    }
}
