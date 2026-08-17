<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Table;


class AssetsExport implements FromCollection, WithHeadings, WithEvents
{
    protected $search;
    protected $device_type;
    protected $status;

    public function __construct($search = null, $device_type = null, $status = null)
    {
        $this->search = $search;
        $this->device_type = $device_type;
        $this->status = $status;
    }

    public function collection()
    {
        $query = Asset::with([
            'hardware',
            'software',
            'assignment'
        ]);

        if ($this->search) {
            $search = $this->search;

            $query->where(function ($query) use ($search) {
                $query->where('asset_tag', 'like', "%{$search}%")
                      ->orWhere('serial_number', 'like', "%{$search}%")
                      ->orWhere('brand', 'like', "%{$search}%")
                      ->orWhere('model', 'like', "%{$search}%");
            });
        }

        if ($this->device_type) {
            $query->where('device_type', $this->device_type);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->get()->map(function ($asset) {

            $active_assignment = $asset->assignment->where('status', 'In Use')->first();
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
                $active_assignment?->user_name,
                $active_assignment?->department,
                $active_assignment?->designation,
                $active_assignment?->location,
                $active_assignment?->inclusion,
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
            'User',
            'Department',
            'Designation',
            'Location',
            'Remarks',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();

                $table = new Table(
                    'A1:' . $lastColumn . $lastRow,
                    'AssetsTable'
                );

                $sheet->addTable($table);

                //Auto fit columns
                foreach (range('A', $lastColumn) as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}