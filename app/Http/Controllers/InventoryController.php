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
        $device_type = DeviceType::where('is_active', true)->get();
        return view('inventories.create', compact('device_type'));
    }
    public function create_multiple()
    {
        $device_type = DeviceType::where('is_active', true)->get();
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

        $deviceType = DeviceType::where('id', $request->device_type)->firstOrFail();

        DB::transaction(function () use ($request, $deviceType) {
            $asset = Asset::create([
                'asset_tag' => $request->asset_tag,
                'device_type_id' => $request->device_type,
                'brand' => $request->brand,
                'model' => $request->model,
                'serial_number' => $request->serial_number,
                'amount' => $request->amount,

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
    public function generate_tag($id)
    {
        $deviceType = DeviceType::where('id', $id)
            ->where('is_active', true)
            ->firstOrFail();

        $prefix = $deviceType->asset_prefix;

        $existingNumbers = Asset::where('asset_tag', 'like', $prefix . '-%')
            ->pluck('asset_tag')
            ->map(function ($tag) use ($prefix) {
                return (int) substr($tag, strlen($prefix) + 1);
            })
            ->toArray();

        $number = 1;

        while (in_array($number, $existingNumbers)) {
            $number++;
        }

        $tag = $prefix . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);

        return response()->json([
            'asset_tag' => $tag
        ]);
    }



    public function display_index(Request $request)
    {
        // $assets = Asset::with(['hardware','assignment','software','audit'])->get();
        $asset_query = Asset::with(['hardware', 'software', 'audit','currentAssignment.department', 'device_type']);
        if ($request->filled('search')) {
            $search = $request->search;
            $asset_query->where(function ($query) use ($search) {

                $query->where('asset_tag', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhereHas('software', function ($softwareQuery) use ($search) {
                        $softwareQuery->where('product_key_os', 'like', "%{$search}%");
                    })
                    
                    ->orWhereHas('currentAssignment', function ($assignmentQuery) use ($search) {
                        $assignmentQuery->where('user_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('currentAssignment.department', function ($departmentQuery) use ($search) {
                        $departmentQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('device_type')) {
            $asset_query->where('device_type_id', $request->device_type);
        }
        if ($request->filled('status')) {
            $asset_query->where('status', $request->status);
        }
        $assets = $asset_query->paginate(10, ['*'], 'asset_page')->withQueryString()->fragment('assets');



        // $assignments = Assignment::with('asset')->paginate(10, ['*'], 'assignment_page');
        $assignment = Assignment::query()
            ->select('assignments.*')
            ->addSelect([
                'has_new_user' => DB::table('assignments as newer')
                    ->selectRaw('1')
                    ->whereColumn('newer.asset_id', 'assignments.asset_id')
                    ->whereColumn('newer.id', '>', 'assignments.id')
                    ->limit(1)
            ])
            ->with([
                'asset',
                'department'
            ]);
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
        $assignments = $assignment->paginate(10, ['*'], 'assignment_page')->withQueryString()->fragment('assignment');


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
        $repair_history = $repair_history_query->paginate(10, ['*'], 'repair_page')->withQueryString()->fragment('repair_history');


        $asset_counts = Asset::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $total_assets = (int) $asset_counts->sum();

        $total_available = (int) ($asset_counts['Available'] ?? 0);
        $total_assigned = (int) ($asset_counts['Assigned'] ?? 0);
        $total_repair = (int) ($asset_counts['For repair'] ?? 0);
        $total_decommissioned = (int) ($asset_counts['Decommissioned'] ?? 0);
        $total_lost = (int) ($asset_counts['Lost'] ?? 0);
        $department = Department::where('is_active', true)->get();
        $total_per_device = DeviceType::withCount('asset')->get();
        $device_type = $total_per_device
            ->where('is_active', true)
            ->values();

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
            'department',
            'total_per_device'
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
        // dd($request->all());
        $deviceType = DeviceType::where(
            'id',
            $request->input('asset.device_type_id')
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

        $filters = $request->input('filters', []);

        return redirect(route('inventory.display_index', $filters))
            ->with('success', 'Asset Successfully Updated')->withFragment('assets');
    }


    public function delete_stock(Asset $asset, Request $request)
    {
        $filters = $request->input('filters', []);
        $asset->delete();
        return redirect(route('inventory.display_index', $filters))->with('success', 'Asset Successfully Deleted')->withFragment('assets');
    }

    public function remove_stock(Asset $asset)
    {
        $assignment_count = $asset->assignment()->count();
        return view('inventories/delete', ['asset' => $asset, 'assignment_count' => $assignment_count]);
    }


    public function confirm_multipleCreate(Request $request)
    {

        // dd($request->all());
        $request->validate([
            'device_type' => 'required|exists:device_types,id',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'identifiers' => 'required|string'
        ]);

        $identifiers = array_values(array_filter(
            array_map('trim', preg_split('/\r\n|\r|\n/', $request->input('identifiers'))),
            fn($value) => $value !== ''
        ));

        if (count($identifiers) !== count(array_unique($identifiers))) {
            return back()
                ->withInput()
                ->withErrors(['identifiers' => 'Duplicate identifier found.']);
        }

        $deviceType = DeviceType::findOrFail($request->device_type);

        $usesProductKey = (bool) $deviceType->identifier;

        $existing = $usesProductKey
            ? Software::whereIn('product_key_os', $identifiers)->pluck('product_key_os')
            : Asset::whereIn('serial_number', $identifiers)->pluck('serial_number');

        if ($existing->isNotEmpty()) {
            return back()
                ->withInput()
                ->withErrors([
                    'identifiers' => 'These identifier already exist: ' . $existing->implode(', ')
                ]);
        }

        $prefix = $deviceType->asset_prefix;
        

       DB::transaction(function () use (
            $request,
            $prefix,
            $identifiers,
            $usesProductKey,
            $deviceType
        ) {
            // Kunin ang mga existing tag para sa prefix na ito
            $existingTags = Asset::where('asset_tag', 'like', $prefix . '-%')
                ->lockForUpdate()
                ->pluck('asset_tag');

            // Itala ang mga numeric suffix ng existing tags
            $usedNumbers = [];

            foreach ($existingTags as $tag) {
                $pattern = '/^' . preg_quote($prefix, '/') . '-(\d+)$/';

                if (preg_match($pattern, $tag, $matches)) {
                    $usedNumbers[(int) $matches[1]] = true;
                }
            }

            $nextNumber = 1;

            foreach ($identifiers as $identifier) {
                // Hanapin ang susunod na vacant na numero
                while (isset($usedNumbers[$nextNumber])) {
                    $nextNumber++;
                }

                $assetTag = $prefix . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

                // Markahan agad bilang nagamit para sa susunod na record sa batch
                $usedNumbers[$nextNumber] = true;
                $nextNumber++;

                $assetData = [
                    'asset_tag' => $assetTag,
                    'device_type_id' => $request->device_type,
                    'brand' => $request->brand,
                    'model' => $request->model,
                    'purchase_date' => $request->purchase_date,
                    'warranty_expiry' => $request->warranty_expiry,
                    'vendor' => $request->vendor,
                    'remarks' => $request->remarks,
                    'amount' => $request->amount,
                ];

                if (!$usesProductKey) {
                    $assetData['serial_number'] = $identifier;
                }

                $asset = Asset::create($assetData);

                Audit::create([
                    'asset_id' => $asset->id,
                    'audit_date' => $request->audit_date,
                ]);

                if ($deviceType->has_hardware) {
                    HardwareSpec::create([
                        'asset_id' => $asset->id,
                        'processor' => $request->processor,
                        'ram_gb' => $request->ram_gb,
                        'storage' => $request->storage,
                        'monitor' => $request->monitor,
                        'gpu' => $request->gpu,
                        'power_supply' => $request->power_supply,
                        'peripherals' => $request->peripherals,
                    ]);

                    $softwareData = [
                        'asset_id' => $asset->id,
                        'operating_system' => $request->operating_system,
                    ];

                    if ($usesProductKey) {
                        $softwareData['product_key_os'] = $identifier;
                    }

                    Software::create($softwareData);
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
        $device_type = DeviceType::where('id', $asset->device_type_id)->firstOrFail();
        return view('inventories.asset_view', compact('asset', 'device_type'));
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
