<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Asset;

class Software extends Model
{
    protected $table = 'software';

    protected $fillable = [
        'asset_id',
        'operating_system',
        'product_key_os',
        'product_key_other'
    ];
    
    // RELATION TO ASSET
     public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
