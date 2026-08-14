<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Assignment;
use App\Models\Audit;
use App\Models\HardwareSpec;
use App\Models\Software;
use App\Models\RepairHistory;

class Asset extends Model
{
    protected $table = 'assets';
    //to create models using terminal type: php artisan make:model ModelName
    protected $fillable = [
        'asset_tag',
        'device_type',
        'brand',
        'model',
        'serial_number',
        'purchase_date',
        'warranty_expiry',
        'vendor',
        'status',
        'remarks'
    ];

    //RELATIONSHIPS BELOW

    public function hardware()
    {
        return $this->hasOne(HardwareSpec::class);
    }
    public function assignment()
    {
        return $this->hasMany(Assignment::class);
    }

    public function software()
    {
        return $this->hasOne(Software::class);
    }
    public function audit()
    {
        return $this->hasOne(Audit::class);
    }

    public function repair_history()
    {
        return $this->hasMany(RepairHistory::class);
    }

    //pag mag add ng option adjust this para ma cover lahat ng type
    public function getDeviceTypeLabelAttribute() 
    {
        return [
            'laptop' => 'Laptop',
            'desktop' => 'Desktop',
            'minipc' => 'Mini PC',
            'aio' => 'All in One',
            'mac' => 'Mac',
            'printer' => 'Printer',
        ][$this->device_type] ?? $this->device_type;
    }
    
    //asset tag
    public static function getAssetPrefix($deviceType)
    {
        return [
            'laptop' => 'LT',
            'desktop' => 'PC',
            'minipc' => 'PC',
            'aio' => 'PC',
            'mac' => 'MAC',
            'printer' => 'PRNTR',
        ][$deviceType] ?? 'IT';
    }

    public function getItemDescriptionAttribute()
    {
        $specs = [];

        if ($this->hardware?->processor) {
            $specs[] = $this->hardware->processor;
        }

        if ($this->hardware?->ram_gb) {
            $specs[] = $this->hardware->ram_gb . 'GB RAM';
        }

        if ($this->hardware?->storage) {
            $specs[] = $this->hardware->storage;
        }
        if ($this->hardware?->gpu) {
            $specs[] = $this->hardware->gpu;
        }
        if ($this->hardware?->power_supply) {
            $specs[] = $this->hardware->power_supply;
        }
        if ($this->hardware?->monitor) {
            $specs[] = $this->hardware->monitor;
        }

        if ($this->software?->operating_system) {
            $specs[] = $this->software->operating_system;
        }

        // Printer
        if ($this->device_type === 'printer') {
            return trim($this->brand . ' ' . $this->model);
        }

        // Desktop - specs only
        if ($this->device_type === 'desktop') {
            return implode(', ', $specs);
        }

        // Laptop / AIO / Mac - Brand + Model + specs
        $description = trim($this->brand . ' ' . $this->model);

        if (!empty($specs)) {
            $description .= ', ' . implode(', ', $specs);
        }

        return $description;
    }

}
