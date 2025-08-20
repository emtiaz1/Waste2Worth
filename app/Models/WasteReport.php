<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteReport extends Model
{
    use HasFactory;

    protected $table = 'wastereport';

    protected $fillable = [
        'waste_type', 'amount', 'unit', 'location', 'description', 'image_path', 'user_email', 'status'
    ];

    /**
     * Get the waste collection associated with this report
     */
    public function wasteCollection()
    {
        return $this->hasOne(WasteCollection::class, 'waste_report_id');
    }
}
