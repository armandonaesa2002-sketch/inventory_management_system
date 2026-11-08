<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Imports\AssetsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Asset;
use App\Models\DeviceType;
use App\Models\HardwareSpec;
use App\Models\Software;
use App\Models\Audit;
use App\Models\Assignment;
use App\Models\Department;
use App\Models\PreparedBy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Throwable;
use App\Exports\FailedAssetsExport;

class AssetImportController extends Controller
{
    public function preview(Request $request)
    {
        // 1. Validate uploaded file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        // 2. Delete previous temporary import file, if any
        $previousPath = session('asset_import_path');

        if ($previousPath) {
            Storage::delete($previousPath);
        }

        // 3. Store uploaded Excel file
        $path = $request->file('file')->store('imports');

        // 4. Save file path in session
        session(['asset_import_path' => $path]);

        // 5. Read Excel file for preview
        $import = new AssetsImport();

        Excel::import($import, Storage::path($path));

        // 6. Retrieve rows
        $rows = $import->getRows();

        $preparedByOptions = PreparedBy::where('is_active', true)->get();

        // 7. Display preview
        return view('assets.import-preview', compact('rows', 'preparedByOptions'));
    }


    public function confirm(Request $request)
    {
        $path = session('asset_import_path');

        if (!$path || !Storage::exists($path)) {
            return redirect()->route('assets.import.form')
                ->with('error', 'Import file not found. Please upload again.');
        }

        // Read Excel rows again
        $import = new AssetsImport();
        Excel::import($import, Storage::path($path));

        $rows = $import->getRows();

        // Check if Excel contains assigned users
        $hasAssignment = $rows->contains(function ($row) {
            return trim((string) ($row['user'] ?? '')) !== ''
                || trim((string) ($row['department'] ?? '')) !== '';
        });

        // Validate Prepared By only if there are assignments
        $request->validate([
            'preparedby_id' => $hasAssignment
                ? 'required|exists:prepared_by_options,id'
                : 'nullable',
        ]);

        $preparedById = $hasAssignment ? $request->preparedby_id : null;
        $ignoreMissingIdentifier = $request->boolean('ignore_missing_identifier');

        $success = 0;
        $failed = [];
        $failedExportRows = [];

        foreach ($rows->values() as $index => $row) {

            // Excel row number (header is row 1)
            $excelRow = $index + 2;

            try {

                DB::transaction(function () use (
                    $row,
                    $preparedById,
                    $ignoreMissingIdentifier
                ) {

                    $getValue = function ($key) use ($row) {
                        $value = trim((string) ($row[$key] ?? ''));
                        return $value !== '' ? $value : null;
                    };

                    // Find Device Type using NAME
                    $deviceTypeName = $getValue('device_type');

                    $deviceType = DeviceType::whereRaw(
                        'LOWER(name) = ?',
                        [strtolower($deviceTypeName ?? '')]
                    )->where('is_active', true)->first();

                    if (!$deviceType) {
                        throw new \Exception(
                            'Device Type not found or inactive: ' .
                                ($deviceTypeName ?? 'Empty')
                        );
                    }

                    // Identifier checking
                    $usesProductKey = (bool) $deviceType->identifier;

                    $serialNumber = $getValue('serial_number');
                    $productKey = $getValue('product_key_os');


                    $missingIdentifier = $usesProductKey
                        ? !$productKey
                        : !$serialNumber;

                    $importReference = null;

                    if ($missingIdentifier && $ignoreMissingIdentifier) {

                        $referenceData = [
                            'device_type_id' => $deviceType->id,
                            'brand' => strtolower($getValue('brand') ?? ''),
                            'model' => strtolower($getValue('model') ?? ''),
                            'purchase_date' => $getValue('purchase_date'),
                            'warranty_expiry' => $getValue('warranty_expiry'),
                            'vendor' => strtolower($getValue('vendor') ?? ''),
                            'processor' => strtolower($getValue('processor') ?? ''),
                            'ram_gb' => $getValue('ram_gb'),
                            'storage' => strtolower($getValue('storage') ?? ''),
                            'monitor' => strtolower($getValue('monitor') ?? ''),
                            'gpu' => strtolower($getValue('gpu') ?? ''),
                            'power_supply' => strtolower($getValue('power_supply') ?? ''),
                            'peripherals' => strtolower($getValue('peripherals') ?? ''),
                            'user' => strtolower($getValue('user') ?? ''),
                            'department' => strtolower($getValue('department') ?? ''),
                        ];

                        $importReference = hash(
                            'sha256',
                            json_encode($referenceData)
                        );

                        if (Asset::where('import_reference', $importReference)->exists()) {
                            throw new \Exception(
                                'Duplicate import: This asset with the same details and assignment was already imported.'
                            );
                        }
                    }


                    if ($usesProductKey) {

                        if (!$productKey && !$ignoreMissingIdentifier) {
                            throw new \Exception('Missing Product Key OS.');
                        }

                        if (
                            $productKey &&
                            Software::where('product_key_os', $productKey)->exists()
                        ) {
                            throw new \Exception('Duplicate Product Key OS: ' . $productKey);
                        }
                    } else {

                        if (!$serialNumber && !$ignoreMissingIdentifier) {
                            throw new \Exception('Missing Serial Number.');
                        }

                        if (
                            $serialNumber &&
                            strtoupper($serialNumber) !== 'N/A' &&
                            Asset::where('serial_number', $serialNumber)->exists()
                        ) {
                            throw new \Exception('Duplicate Serial Number: ' . $serialNumber);
                        }
                    }

                    // Generate Asset Tag
                    $prefix = $deviceType->asset_prefix;

                    $lastAsset = Asset::where('asset_tag', 'like', $prefix . '-%')
                        ->orderByDesc('id')
                        ->first();

                    $lastNumber = $lastAsset
                        ? (int) substr($lastAsset->asset_tag, strlen($prefix) + 1)
                        : 0;

                    $assetTag = $prefix . '-' .
                        str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

                    // Create Asset
                    $asset = Asset::create([
                        'asset_tag' => $assetTag,
                        'device_type_id' => $deviceType->id,
                        'brand' => $getValue('brand'),
                        'model' => $getValue('model'),
                        'serial_number' => $serialNumber,

                        'purchase_date' => $deviceType->has_purchase
                            ? $getValue('purchase_date') : null,

                        'warranty_expiry' => $deviceType->has_purchase
                            ? $getValue('warranty_expiry') : null,

                        'vendor' => $deviceType->has_purchase
                            ? $getValue('vendor') : null,

                        'remarks' => $getValue('remarks'),

                        'status' => ($getValue('user') || $getValue('department')) ? 'Assigned' : 'Available',
                        'import_reference' => $importReference,
                    ]);

                    // Create Audit
                    Audit::create([
                        'asset_id' => $asset->id,
                        'audit_date' => null,
                    ]);

                    // Create Hardware Specs
                    if ($deviceType->has_hardware) {

                        HardwareSpec::create([
                            'asset_id' => $asset->id,
                            'processor' => $getValue('processor'),
                            'ram_gb' => $getValue('ram_gb'),
                            'storage' => $getValue('storage'),
                            'monitor' => $getValue('monitor'),
                            'gpu' => $getValue('gpu'),
                            'power_supply' => $getValue('power_supply'),
                            'peripherals' => $getValue('peripherals'),
                        ]);
                    }

                    // Create Software
                    if ($deviceType->has_license_notes) {

                        Software::create([
                            'asset_id' => $asset->id,
                            'operating_system' => $getValue('operating_system'),
                            'product_key_os' => $usesProductKey
                                ? $productKey
                                : $getValue('product_key_os'),
                            'product_key_other' => $getValue('product_key_others'),
                        ]);
                    }

                    // Create Assignment only if User exists
                    $user = $getValue('user');
                    $departmentName = $getValue('department');

                    if ($user || $departmentName) {

                        $departmentName = $getValue('department');

                        $department = Department::whereRaw(
                            'LOWER(name) = ?',
                            [strtolower($departmentName ?? '')]
                        )->where('is_active', true)->first();

                        if (!$department) {
                            throw new \Exception(
                                'Department not found or inactive: ' .
                                    ($departmentName ?? 'Empty')
                            );
                        }

                        Assignment::create([
                            'asset_id' => $asset->id,
                            'user_name' => $user,
                            'department_id' => $department->id,
                            'designation' => $getValue('designation'),
                            'location' => $getValue('location'),
                            'inclusion' => $getValue('inclusion'),
                            'status' => 'In Use',
                            'preparedby_id' => $preparedById,
                            'release_date' => $getValue('release_date'),
                        ]);
                    }
                });

                $success++;
            } catch (Throwable $e) {

                $reason = $e instanceof \Illuminate\Database\QueryException
                    ? 'Database error while saving this row.'
                    : $e->getMessage();

                $failed[] = [
                    'row' => $excelRow,
                    'reason' => $reason,
                ];

                $failedExportRows[] = [
                    'row' => $excelRow,
                    'data' => is_array($row) ? $row : $row->toArray(),
                    'reason' => $reason,
                ];
            }
        }

        session([
            'failed_import_rows' => $failedExportRows
        ]);

        // Delete temporary Excel file
        Storage::delete($path);
        session()->forget('asset_import_path');

        return view('assets.import-result', compact(
            'success',
            'failed'
        ));
    }

    public function downloadFailed()
    {
        $failedRows = session('failed_import_rows', []);

        if (empty($failedRows)) {
            return redirect()->route('assets.import.form')
                ->with('error', 'No failed records available.');
        }

        return Excel::download(
            new FailedAssetsExport($failedRows),
            'failed-assets.xlsx'
        );
    }
}
