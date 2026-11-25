<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Table;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;


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
            'assignment',
            'currentAssignment'
        ]);

        if ($this->search) {
            $search = $this->search;

            $query->where(function ($query) use ($search) {
                $query->where('asset_tag', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%");
                })
                ->orWhereHas('currentAssignment', function ($assignmentQuery) use ($search) {
                    $assignmentQuery->where('user_name', 'like', "%{$search}%");
                });
        }

        if ($this->device_type) {
            $query->where('device_type_id', $this->device_type);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->get()->map(function ($asset) {

            $active_assignment = $asset->assignment->where('status', 'In Use')->first();
            return [
                $asset->asset_tag,
                $asset->device_type->name,
                implode(', ', array_filter([
                    $asset->brand,
                    $asset->model,
                    $asset->hardware?->processor,
                    $asset->hardware?->ram_gb,
                    $asset->hardware?->storage,
                    $asset->hardware?->monitor,
                    $asset->hardware?->gpu,
                    $asset->hardware?->power_supply,
                    $asset->software?->operating_system,
                    $asset->hardware?->peripherals,
                    $active_assignment?->inclusion,
                ], fn($value) => !empty($value))),
                $active_assignment?->user_name,
                $active_assignment?->department?->name,
                $active_assignment?->release_date?->format('m/d/Y'),
                $asset->serial_number,
                $active_assignment?->location,
                $active_assignment?->designation,
                $asset->amount,
                $asset->software?->product_key_os,
                $asset->software?->product_key_other,
                $asset->status,
                $asset->purchase_date?->format('m/d/Y'),
                $asset->warranty_expiry?->format('m/d/Y'),
                $asset->vendor,
                $asset->remarks,
                $asset->id,
                


            ];
        });
    }

    public function headings(): array
    {
        return [
            'Asset Tag',
            'Device Type',
            'Item Description',
            'User',
            'Department',
            'Release Date',
            'Serial Number',
            'Location',
            'Designation',
            'Amount',
            'Product Key OS',
            'Product Key Other',
            'Status',
            'Purchase Date',
            'Warranty Expiry',
            'Vendor',
            'Remarks',
            'Asset Id',
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

                // Auto fit columns (supports beyond column Z)
                $highestColumnIndex = Coordinate::columnIndexFromString($lastColumn);

                for ($col = 1; $col <= $highestColumnIndex; $col++) {
                    $columnLetter = Coordinate::stringFromColumnIndex($col);
                    $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
                }

                    // Wrap text for Item Description (example: Column B)
                $sheet->getStyle('C2:C' . $lastRow)
                    ->getAlignment()
                    ->setWrapText(true);

                // Set fixed width for Item Description
                $sheet->getColumnDimension('C')->setAutoSize(false);
                $sheet->getColumnDimension('C')->setWidth(80);

            },
        ];
    }
}
