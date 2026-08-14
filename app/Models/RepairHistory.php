<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Asset;

class RepairHistory extends Model
{
    protected $table = 'repair_history';

    protected $fillable = [
        'asset_id',
        'assignment_id',
        'asset_tag',
        'user_name',
        'type',
        'repair_status',
        'description',
        'return_outcome',
        'previous_remarks',
        'repair_status_after'
    ];

    // RELATION TO Assignment
     public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
