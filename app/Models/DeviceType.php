<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];
}
