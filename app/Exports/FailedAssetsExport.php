<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FailedAssetsExport implements FromArray, WithHeadings
{
    protected $rows;

    public function __construct($rows)
    {
        $this->rows = $rows;
    }

    public function headings(): array
    {
        return [
            'Excel Row',
            'Device Type',
            'Brand',
            'Model',
            'Serial Number',
            'Purchase Date',
            'Warranty Expiry',
            'Vendor',
            'Processor',
            'RAM (GB)',
            'Storage',
            'Monitor',
            'GPU',
            'Power Supply',
            'Peripherals',
            'Operating System',
            'Product Key OS',
            'Product Key Others',
            'User',
            'Department',
            'Designation',
            'Location',
            'Remarks',
            'Import Error',
        ];
    }

    public function array(): array
    {
        return array_map(function ($item) {
            $data = $item['data'];

            return [
                $item['row'],
                $data['device_type'] ?? '',
                $data['brand'] ?? '',
                $data['model'] ?? '',
                $data['serial_number'] ?? '',
                $data['purchase_date'] ?? '',
                $data['warranty_expiry'] ?? '',
                $data['vendor'] ?? '',
                $data['processor'] ?? '',
                $data['ram_gb'] ?? '',
                $data['storage'] ?? '',
                $data['monitor'] ?? '',
                $data['gpu'] ?? '',
                $data['power_supply'] ?? '',
                $data['peripherals'] ?? '',
                $data['operating_system'] ?? '',
                $data['product_key_os'] ?? '',
                $data['product_key_others'] ?? '',
                $data['user'] ?? '',
                $data['department'] ?? '',
                $data['designation'] ?? '',
                $data['location'] ?? '',
                $data['remarks'] ?? '',
                $item['reason'],
            ];
        }, $this->rows);
    }
}