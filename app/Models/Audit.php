<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Asset;

class Audit extends Model
{
    protected $table = 'audits';

    protected $fillable = [
        'asset_id',
        'audit_date'
    ];

    // RELATION TO ASSET
     public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
