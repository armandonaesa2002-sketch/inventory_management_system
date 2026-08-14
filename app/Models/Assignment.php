<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Asset;

class Assignment extends Model
{
    protected $table = 'assignments';

    protected $fillable = [
        'asset_id',
        'user_name',
        'department',
        'location',
        'status',
        'remarks',
        'designation',
        'inclusion',
        'prepared_by'
    ];

    // RELATION TO ASSET
     public function asset()    
    {
        return $this->belongsTo(Asset::class);
    }
}
