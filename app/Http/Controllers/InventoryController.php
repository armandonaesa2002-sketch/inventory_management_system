<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Asset;
use App\Models\DeviceType;
use App\Models\Assignment;
use App\Models\Audit;
use App\Models\HardwareSpec;
use App\Models\RepairHistory;
use App\Models\Software;
use App\Models\Department;
use App\Exports\AssetsExport;
use Maatwebsite\Excel\Facades\Excel;

class InventoryController extends Controller
{

    public function create()
    {
        $device_type = DeviceType::all();
        return view('inventories.create', compact('device_type'));
    }
    public function create_multiple()
    {
        $device_type = DeviceType::all();
        return view('inventories.multiple', compact('device_type'));
    }

    public function add_stock(Request $request)
    {
        //main asset
        if (strtoupper(trim($request->serial_number)) !== 'N/A') {
            $existing = Asset::where('serial_number', $request->serial_number)->exists();
            if ($existing) {
                return back()
                    ->withInput()
                    ->with(
                        'serial_number',
                        'This serial number already exist: ' . $request->serial_number
                    );
            }
        }

        $deviceType = DeviceType::where('code', $request->device_type)->firstOrFail();

        DB::transaction(function () use ($request, $deviceType) {
            $asset = Asset::create([
                'asset_tag' => $request->asset_tag,
                'device_type' => $request->device_type,
                'brand' => $request->brand,
                'model' => $request->model,
                'serial_number' => $request->serial_number,

                'purchase_date' => $deviceType->has_purchase ? $request->purchase_date : null,
                'warranty_expiry' => $deviceType->has_purchase ? $request->warranty_expiry : null,
                'vendor' => $deviceType->has_purchase ? $request->vendor : null,

                'remarks' => $deviceType->has_license_notes ? $request->remarks : null
            ]);

            $audit = Audit::create([
                'asset_id' => $asset->id,
                'audit_date' => $request->audit_date
            ]);

            if ($deviceType->has_hardware) {

                $hardware_specs = HardwareSpec::create([
                    'asset_id' => $asset->id,
                    'processor' => $request->processor,
                    'ram_gb' => $request->ram_gb,
                    'storage' => $request->storage,
                    'monitor' => $request->monitor,
                    'gpu' => $request->gpu,
                    'power_supply' => $request->power_supply,
                    'peripherals' => $request->peripherals
                ]);
            }
            if ($deviceType->has_license_notes) {

                $software = Software::create([
                    'asset_id' => $asset->id,
                    'operating_system' => $request->operating_system,
                    'product_key_os' => $request->product_key_os,
                    'product_key_other' => $request->product_key_other
                ]);
            }
        });

        return redirect(route('inventory.display_index'))->with('success', 'Asset Successfully Added');
    }

    //Generating Tag to Json
    public function generate_tag($type)
    {
        $deviceType = DeviceType::where('code', $type)
            ->where('is_active', true)
            ->firstOrFail();

        $prefix = $deviceType->asset_prefix;

        $last = Asset::where('asset_tag', 'like', $prefix . '-%')
            ->orderBy('asset_tag', 'desc')
            ->first();

        $number = 1;

        if ($last) {
            $parts = explode('-', $last->asset_tag);
            $number = (int)$parts[1] + 1;
        }

        $tag = $prefix . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);

        return response()->json([
            'asset_tag' => $tag
        ]);
    }


    public function display_index(Request $request)
    {
        // $assets = Asset::with(['hardware','assignment','software','audit'])->get();
        $asset_query = Asset::with(['hardware', 'software', 'audit']);
        if ($request->filled('search')) {
            $search = $request->search;
            $asset_query->where(function ($query) use ($search) {

                $query->where('asset_tag', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%");
            });
        }

        if ($request->filled('device_type')) {
            $asset_query->where('device_type', $request->device_type);
        }
        if ($request->filled('status')) {
            $asset_query->where('status', $request->status);
        }
        $assets = $asset_query->paginate(10, ['*'], 'asset_page')->withQueryString();



        // $assignments = Assignment::with('asset')->paginate(10, ['*'], 'assignment_page');
        $assignment = Assignment::with('asset');
        if ($request->filled('assignment_search')) {
            $assignment_search = $request->assignment_search;
            $assignment->where(function ($assignment_query) use ($assignment_search) {

                $assignment_query->whereHas('asset', function ($q) use ($assignment_search) {
                    $q->where('asset_tag', 'like', "%{$assignment_search}%");
                })
                    ->orWhere('user_name', 'like', "%{$assignment_search}%");
            });
        }
        if ($request->filled('department')) {
            $assignment->where('department_id', $request->department);
        }
        if ($request->filled('assignment_status')) {
            $assignment->where('status', $request->assignment_status);
        }
        $assignments = $assignment->paginate(10, ['*'], 'assignment_page')->withQueryString();


        // $repair_history = RepairHistory::with('asset')->paginate(10, ['*'], 'repair_page');
        $repair_history_query = RepairHistory::with('asset');
        if ($request->filled('repair_search')) {
            $repair_search = $request->repair_search;
            $repair_history_query->where(function ($repair) use ($repair_search) {
                $repair->whereHas('asset', function ($r) use ($repair_search) {
                    $r->where('asset_tag', 'like', "%{$repair_search}%");
                })
                    ->orWhere('user_name', 'like', "%{$repair_search}%");
            });
        }
        if ($request->filled('repair_status')) {
            $repair_history_query->where('repair_status', $request->repair_status);
        }
        $repair_history = $repair_history_query->paginate(10, ['*'], 'repair_page')->withQueryString();


        $total_assets = Asset::count();
        $total_available = Asset::where('status', 'Available')->count();
        $total_assigned = Asset::where('status', 'Assigned')->count();
        $total_repair = Asset::where('status', 'For repair')->count();
        $total_decommissioned = Asset::where('status', 'Decommissioned')->count();
        $total_lost = Asset::where('status', 'Lost')->count();
        $device_type = DeviceType::all();
        $department = Department::all();

        $data = array_merge(compact(
            'assets',
            'assignments',
            'repair_history',
            'total_assets',
            'total_available',
            'total_assigned',
            'total_repair',
            'total_decommissioned',
            'total_lost',
            'device_type',
            'department'
        ));

        return view('inventories.index', compact('data'));
    }

    public function edit_stock(Asset $asset)
    {
        // dd($asset);
        $device_type = DeviceType::all();
        return view('inventories.edit', ['asset' => $asset, 'device_type' => $device_type]);
    }

    public function update_asset(Asset $asset, Request $request)
    {
        $deviceType = DeviceType::where(
            'code',
            $request->input('asset.device_type')
        )->firstOrFail();

        // ASSET
        $asset->fill($request->input('asset'));

        if (!$deviceType->has_purchase) {
            $asset->purchase_date = null;
            $asset->warranty_expiry = null;
            $asset->vendor = null;
        }

        // Check Asset changes
        $assetChanged = $asset->isDirty();

        // Existing child records
        $hardware = $asset->hardware;
        $software = $asset->software;

        // Check Hardware changes
        $hardwareChanged = false;

        if ($deviceType->has_hardware) {
            if ($hardware) {
                $hardware->fill($request->input('hardware', []));
                $hardwareChanged = $hardware->isDirty();
            } else {
                $hardwareChanged = true; // kailangan gumawa ng bagong record
            }
        }

        // Check Software changes
        $softwareChanged = false;

        if ($deviceType->has_license_notes) {
            if ($software) {
                $software->fill($request->input('software', []));
                $softwareChanged = $software->isDirty();
            } else {
                $softwareChanged = true; // kailangan gumawa ng bagong record
            }
        }

        // NO CHANGE
        if (!$assetChanged && !$hardwareChanged && !$softwareChanged) {
            return back()->with('info', 'No change Made');
        }

        DB::transaction(function () use (
            $asset,
            $hardware,
            $software,
            $request,
            $deviceType
        ) {

            $asset->save();

            if ($deviceType->has_hardware) {
                $asset->hardware()->updateOrCreate(
                    ['asset_id' => $asset->id],
                    $request->input('hardware', [])
                );
            }

            if ($deviceType->has_license_notes) {
                $asset->software()->updateOrCreate(
                    ['asset_id' => $asset->id],
                    $request->input('software', [])
                );
            }
        });

        return redirect(route('inventory.display_index'))
            ->with('success', 'Asset Successfully Updated');
    }


    public function delete_stock(Asset $asset)
    {
        $asset->delete();
        return redirect(route('inventory.display_index'))->with('success', 'Asset Successfully Deleted');
    }

    public function remove_stock(Asset $asset)
    {
        $assignment_count = $asset->assignment()->count();
        return view('inventories/delete', ['asset' => $asset, 'assignment_count' => $assignment_count]);
    }


    public function confirm_multipleCreate(Request $request)
    {

        $request->validate([
            'device_type' => 'required',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_numbers' => 'required|string'
        ]);

        $serials = array_filter(array_map(
            'trim',
            preg_split('/\r\n|\r|\n/', $request->serial_numbers)
        ));

        if (count($serials) !== count(array_unique($serials))) {
            return back()
                ->withInput()
                ->withErrors([
                    'serial_numbers' => 'Duplicate Serial numbers found.'
                ]);
        }

        $existing = Asset::whereIn('serial_number', $serials)->pluck('serial_number');

        if ($existing->isNotEmpty()) {
            return back()
                ->withInput()
                ->withErrors([
                    'serial_numbers' => 'These serial numbers already exist: ' . $existing->implode(', ')
                ]);
        }

        // $data = [];

        $prefix = Asset::getAssetPrefix($request->device_type);
        $lastAsset = Asset::where('asset_tag', 'like', $prefix . '-%')
            ->latest('id')
            ->first();
        if ($lastAsset) {
            $lastNumber = (int) substr($lastAsset->asset_tag, strlen($prefix) + 1);
        } else {
            $lastNumber = 0;
        }

        DB::transaction(function () use ($request, $serials, $lastNumber, $prefix) {
            foreach ($serials as $serial) {
                $lastNumber++;

                $asset_tag = $prefix . '-' . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);
                //main asset
                $asset = Asset::create([
                    'asset_tag' => $asset_tag,
                    'device_type' => $request->device_type,
                    'brand' => $request->brand,
                    'model' => $request->model,
                    'serial_number' => $serial,
                    'purchase_date' => $request->purchase_date,
                    'warranty_expiry' => $request->warranty_expiry,
                    'vendor' => $request->vendor,
                    'remarks' => $request->remarks
                ]);

                $audit = Audit::create([
                    'asset_id' => $asset->id,
                    'audit_date' => $request->audit_date
                ]);

                // Computer fields only
                if ($request->device_type !== 'printer') {
                    $hardware_specs = HardwareSpec::create([
                        'asset_id' => $asset->id,
                        'processor' => $request->processor,
                        'ram_gb' => $request->ram_gb,
                        'storage' => $request->storage,
                        'monitor' => $request->monitor,
                        'gpu' => $request->gpu,
                        'power_supply' => $request->power_supply,
                        'peripherals' => $request->peripherals
                    ]);

                    $software = Software::create([
                        'asset_id' => $asset->id,
                        'operating_system' => $request->operating_system,
                        'product_key_os' => $request->product_key_os,
                        'product_key_other' => $request->product_key_other
                    ]);
                }
            }
        });

        return redirect()
            ->route('inventory.display_index')
            ->with('success', 'Assets created successfully.');
    }

    public function view_asset(Asset $asset)
    {

        $asset->load([
            'hardware',
            'assignment' => function ($query) {
                $query->latest();
            },
            'software',
            'repair_history' => function ($query) {
                $query->latest();
            },
        ]);

        return view('inventories.asset_view', ['asset' => $asset]);
    }

    public function export(Request $request)
    {
        return Excel::download(
            new AssetsExport(
                $request->search,
                $request->device_type,
                $request->status
            ),
            'assets_' . now()->format('Y-m-d_His') . '.xlsx'
        );
    }
}
