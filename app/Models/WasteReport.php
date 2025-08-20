<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteReport extends Model
{
    use HasFactory;

    protected $table = 'wastereport';

    protected $fillable = [
        'user_email',
        'waste_type',
        'status',
        'amount',
        'unit',
        'location',
        'description',
        'image_path',
    ];

    /**
     * Get the waste collection associated with this report
     */
    public function wasteCollection()
    {
        return $this->hasOne(WasteCollection::class, 'waste_report_id');
    }

    /**
     * Get all collection requests for this report
     */
    public function collectionRequests()
    {
        return $this->hasMany(WasteCollection::class, 'waste_report_id');
    }

    /**
     * Get the user profile who reported this waste
     */
    public function reporterProfile()
    {
        return $this->belongsTo(Profile::class, 'user_email', 'email');
    }

    /**
     * Get all waste collections associated with this report
     */
    public function collections()
    {
        return $this->hasMany(WasteCollection::class, 'waste_report_id');
    }
}
