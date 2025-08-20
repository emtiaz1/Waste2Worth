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
            'collected' => self::where('status', 'collected')->count(),
            'completed' => self::where('status', 'completed')->count(),
            'total_weight_collected' => self::where('status', 'completed')
                ->sum('actual_weight'),
            'today_collections' => self::whereDate('collected_at', today())
                ->where('status', 'completed')
                ->count()
        ];
    }
}
