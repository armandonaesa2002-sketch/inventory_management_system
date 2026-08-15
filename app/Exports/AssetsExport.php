<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssetsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Asset::with([
            'hardware',
            'software',
        ])->get()->map(function ($asset) {

            return [
                $asset->asset_tag,
                $asset->device_type_label,
                $asset->brand,
                $asset->model,
                $asset->serial_number,
                $asset->purchase_date,
                $asset->warranty_expiry,
                $asset->vendor,

                $asset->hardware?->processor,
                $asset->hardware?->ram_gb,
                $asset->hardware?->storage,
                $asset->hardware?->monitor,
                $asset->hardware?->gpu,
                $asset->hardware?->power_supply,
                $asset->hardware?->peripherals,

                $asset->software?->operating_system,
                $asset->software?->product_key_os,
                $asset->software?->product_key_other,

                $asset->status,
                $asset->remarks,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Asset Tag',
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
            'Status',
            'Remarks',
        ];
    }
}