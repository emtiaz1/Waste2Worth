<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteReport extends Model
{
    use HasFactory;

    protected $table = 'wastereport';

    protected $fillable = [
        'waste_type', 'amount', 'unit', 'location', 'description', 'image_path'
    ];
}
