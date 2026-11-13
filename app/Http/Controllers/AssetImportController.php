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
use App\Models\RepairHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Throwable;
use App\Exports\FailedAssetsExport;
use App\Models\AssetImportBatch;

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

        // 4. Save file path and original filename in session
        session([
            'asset_import_path' => $path,
            'asset_import_original_name' => $request->file('file')->getClientOriginalName(),
        ]);

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
        $originalFileName = session('asset_import_original_name');

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

        $batch = AssetImportBatch::create([
            'batch_code' => 'IMP-' . now()->format('YmdHis') . '-' . strtoupper(\Illuminate\Support\Str::random(4)),
            'file_name' => $originalFileName,
            'imported_by' => auth()->id(),
            'total_success' => 0,
            'total_failed' => 0,
            'imported_at' => now(),
        ]);


        $parseDate = function ($value) {
            if ($value === null) {
                return null;
            }

            // DateTime object
            if ($value instanceof \DateTimeInterface) {
                return $value->format('Y-m-d');
            }

            $value = trim((string) $value);

            if ($value === '') {
                return null;
            }

            // Excel serial date
            if (is_numeric($value)) {
                $tempDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(
                    (float) $value
                );

                $excelMonth = (int) $tempDate->format('n');
                $excelDay   = (int) $tempDate->format('j');
                $excelYear  = (int) $tempDate->format('Y');

                // Panatilihin ang custom na month/day flip para sa file mo
                if ($excelDay <= 12) {
                    try {
                        return \Carbon\Carbon::createFromFormat(
                            '!n/j/Y',
                            "{$excelDay}/{$excelMonth}/{$excelYear}"
                        )->format('Y-m-d');
                    } catch (\InvalidArgumentException $e) {
                        return $tempDate->format('Y-m-d');
                    }
                }

                return $tempDate->format('Y-m-d');
            }

            // MM/DD/YY o MM/DD/YYYY
            $format = preg_match('/^\d{1,2}\/\d{1,2}\/\d{2}$/', $value)
                ? '!n/j/y'
                : '!n/j/Y';

            try {
                return \Carbon\Carbon::createFromFormat($format, $value)->format('Y-m-d');
            } catch (\InvalidArgumentException $e) {
                return null;
            }
        };

        



        foreach ($rows->values() as $index => $row) {

            // Excel row number (header is row 1)
            $excelRow = $index + 2;

            try {

                DB::transaction(function () use (
                    $row,
                    $preparedById,
                    $ignoreMissingIdentifier,
                    $parseDate,
                    $batch
                ) {

                    $getValue = function ($key) use ($row) {
                        $value = trim((string) ($row[$key] ?? ''));
                        return $value !== '' ? $value : null;
                    };

                    $assetId = $getValue('asset_id');

                    // Check Asset ID from exported Excel
                    if ($assetId !== null) {

                        if (!filter_var($assetId, FILTER_VALIDATE_INT) || (int) $assetId <= 0) {
                            throw new \Exception('Invalid Asset ID: ' . $assetId);
                        }

                        if (Asset::where('id', $assetId)->exists()) {
                            throw new \Exception('Duplicate Asset ID: ' . $assetId);
                        }

                        throw new \Exception('Asset ID not found: ' . $assetId);
                    }

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

                    $serialNumber = strtoupper(trim($getValue('serial_number') ?? ''));

                    $serialNumber = $serialNumber === '' || $serialNumber === 'N/A'
                        ? null
                        : $serialNumber;
                    $productKey = $getValue('product_key_os');


                    $missingIdentifier = $usesProductKey
                        ? !$productKey
                        : !$serialNumber;

                    $importReference = null;

                    if ($missingIdentifier && $ignoreMissingIdentifier) {

                        $normalize = function ($value) {
                            if ($value === null) {
                                return '';
                            }

                            return strtolower(trim((string) $value));
                        };

                        $referenceData = [
                            'device_type_id' => (int) $deviceType->id,
                            'brand' => $normalize($getValue('brand')),
                            'model' => $normalize($getValue('model')),
                            'purchase_date' => $normalize($getValue('purchase_date')),
                            'warranty_expiry' => $normalize($getValue('warranty_expiry')),
                            'vendor' => $normalize($getValue('vendor')),
                            'processor' => $normalize($getValue('processor')),
                            'ram_gb' => $normalize($getValue('ram_gb')),
                            'storage' => $normalize($getValue('storage')),
                            'monitor' => $normalize($getValue('monitor')),
                            'gpu' => $normalize($getValue('gpu')),
                            'power_supply' => $normalize($getValue('power_supply')),
                            'peripherals' => $normalize($getValue('peripherals')),
                            'user' => $normalize($getValue('user')),
                            'department' => $normalize($getValue('department')),
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
                            ? $parseDate($row['purchase_date'] ?? null) : null,

                        'warranty_expiry' => $deviceType->has_purchase
                            ? $parseDate($row['warranty_expiry'] ?? null) : null,

                        'vendor' => $deviceType->has_purchase
                            ? $getValue('vendor') : null,

                        'remarks' => $getValue('remarks'),

                        'status' => ($getValue('user') || $getValue('department')) ? 'Assigned' : 'Available',
                        'import_reference' => $importReference,
                        'import_batch_id' => $batch->id,
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
                            'release_date' => $parseDate($row['release_date'] ?? null),
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

        if ($success > 0) {
            $batch->update([
                'total_success' => $success,
                'total_failed' => count($failed),
                'completed_at' => now(),
            ]);
        } else {
            $batch->delete();
            $batch = null;
        }

        session([
            'failed_import_rows' => $failedExportRows
        ]);

        // Delete temporary Excel file
        Storage::delete($path);
        session()->forget('asset_import_path');

        return view('assets.import-result', compact(
            'success',
            'failed',
            'batch'
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

    
    public function revert(Request $request, $id)
    {
        $redirectRoute = $request->input('return_to') === 'settings'
            ? 'inventory.settings'
            : 'assets.import.form';

        $batch = AssetImportBatch::findOrFail($id);

        // Check if already reverted
        if ($batch->reverted_at) {
            return redirect()->route($redirectRoute)
                ->with('error', 'This import batch has already been reverted.')->withFragment('import_alert');
        }

        // Check if completion timestamp exists
        if (!$batch->completed_at) {
            return redirect()->route($redirectRoute)
                ->with('error', 'Cannot safely revert this batch. Completion timestamp is missing.')->withFragment('import_alert');
        }

        $completedAt = $batch->completed_at;

        // Get imported asset IDs
        $assetIds = $batch->assets()->pluck('id');

        if ($assetIds->isEmpty()) {
            return redirect()->route($redirectRoute)
                ->with('error', 'No imported assets found in this batch.')->withFragment('import_alert');
        }

        // Check if imported assets were modified after import
        $hasChanges = $batch->assets()
            ->where(function ($query) use ($completedAt) {
                $query->where('created_at', '>', $completedAt)
                    ->orWhere('updated_at', '>', $completedAt);
            })
            ->exists();

        // Check all related records
        $relatedModels = [
            Assignment::class,
            Audit::class,
            HardwareSpec::class,
            Software::class,
            RepairHistory::class,
        ];

        foreach ($relatedModels as $model) {

            $hasRelatedChanges = $model::whereIn('asset_id', $assetIds)
                ->where(function ($query) use ($completedAt) {
                    $query->where('created_at', '>', $completedAt)
                        ->orWhere('updated_at', '>', $completedAt);
                })
                ->exists();

            if ($hasRelatedChanges) {
                $hasChanges = true;
                break;
            }
        }

        // Block revert if changes were detected
        if ($hasChanges) {
            return redirect()->route($redirectRoute)
                ->with('error', 'Cannot revert this batch because some imported assets or related records have been modified after import.')->withFragment('import_alert');
        }

        // Safe to revert
        DB::transaction(function () use ($batch) {

            $batch->assets()->delete();

            $batch->update([
                'reverted_at' => now(),
            ]);
        });

        return redirect()->route($redirectRoute)->with(
            'success',
            'Import batch ' . $batch->batch_code . ' has been successfully reverted.'
        )->withFragment('import_alert');
    }

}
