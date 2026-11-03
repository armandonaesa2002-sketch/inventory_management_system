<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Asset;

class DeviceType extends Model
{
    protected $table = 'device_types';

    protected $fillable = [
        'code',
        'name',
        'asset_prefix',
        'is_active',
        'has_basic',
        'has_hardware',
        'has_purchase',
        'has_license_notes',
        'identifier',
    ];

    public function asset()
    {
        return $this->hasMany(Asset::class);
    }
}
