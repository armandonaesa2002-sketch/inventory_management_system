<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\DeviceType;
use Illuminate\Database\Seeder;

class DeviceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DeviceType::insert([
            [
                'code' => 'laptop',
                'name' => 'Laptop',
                'asset_prefix' => 'LT',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'has_basic' => true,
                'has_hardware' => true,
                'has_purchase' => true,
                'has_license_notes' => true,
            ],
            [
                'code' => 'desktop',
                'name' => 'Desktop',
                'asset_prefix' => 'PC',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'has_basic' => true,
                'has_hardware' => true,
                'has_purchase' => true,
                'has_license_notes' => true,
            ],
            [
                'code' => 'minipc',
                'name' => 'Mini PC',
                'asset_prefix' => 'PC',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'has_basic' => true,
                'has_hardware' => true,
                'has_purchase' => true,
                'has_license_notes' => true,
            ],
            [
                'code' => 'aio',
                'name' => 'All in One',
                'asset_prefix' => 'PC',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'has_basic' => true,
                'has_hardware' => true,
                'has_purchase' => true,
                'has_license_notes' => true,
            ],
            [
                'code' => 'mac',
                'name' => 'Mac',
                'asset_prefix' => 'MAC',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'has_basic' => true,
                'has_hardware' => true,
                'has_purchase' => true,
                'has_license_notes' => true,
            ],
            [
                'code' => 'printer',
                'name' => 'Printer',
                'asset_prefix' => 'PRNTR',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'has_basic' => true,
                'has_hardware' => false,
                'has_purchase' => true,
                'has_license_notes' => false,
            ],
        ]);
    }
}
