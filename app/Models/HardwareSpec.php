<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Asset;

class HardwareSpec extends Model
{
    //
    protected $table = 'hardware_specs';

    protected $fillable = [
        'asset_id',
        'processor',
        'ram_gb',
        'storage',
        'monitor',
        'gpu',
        'power_supply',
        'peripherals'
    ];

    // RELATION TO ASSET
     public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
