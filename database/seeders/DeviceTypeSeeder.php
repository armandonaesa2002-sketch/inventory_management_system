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
            ],
            [
                'code' => 'desktop',
                'name' => 'Desktop',
                'asset_prefix' => 'PC',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'minipc',
                'name' => 'Mini PC',
                'asset_prefix' => 'PC',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'aio',
                'name' => 'All in One',
                'asset_prefix' => 'PC',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'mac',
                'name' => 'Mac',
                'asset_prefix' => 'MAC',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'printer',
                'name' => 'Printer',
                'asset_prefix' => 'PRNTR',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
