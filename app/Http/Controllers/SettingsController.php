<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\PreparedBy;
use App\Models\DeviceType;
use App\Models\Department;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function settings()
    {
        $device_types = DeviceType::all();
        $departments = Department::all();
        $preparedby_options = PreparedBy::all();
        $data = array_merge(compact(
            'device_types',
            'departments',
            'preparedby_options'

        ));
        return view('inventories.settings', compact('data'));
    }
    public function add_device_type(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:device_types,code'],
            'name' => ['required', 'string', 'max:100', 'unique:device_types,name'],
            'asset_prefix' => ['required', 'string', 'max:20'],
            'has_basic' => ['required', 'boolean'],
            'has_hardware' => ['sometimes', 'boolean'],
            'has_purchase' => ['sometimes', 'boolean'],
            'has_license_notes' => ['sometimes', 'boolean'],
        ]);

        DeviceType::create([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'asset_prefix' => $validated['asset_prefix'],
            'has_basic' => true,
            'has_hardware' => $request->boolean('has_hardware'),
            'has_purchase' => $request->boolean('has_purchase'),
            'has_license_notes' => $request->boolean('has_license_notes'),
        ]);

        return back()->with('success-devicetype', 'Device type added successfully.');
    }

    public function update(DeviceType $device_type, Request $request)
    {
        // $device_type = DeviceType::findOrFail($device_type);

        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('device_types', 'code')->ignore($device_type->id),
            ],
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('device_types', 'name')->ignore($device_type->id),
            ],
            'asset_prefix' => ['required', 'string', 'max:20'],
            'has_hardware' => ['sometimes', 'boolean'],
            'has_purchase' => ['sometimes', 'boolean'],
            'has_license_notes' => ['sometimes', 'boolean'],
        ]);

        $changes = [
            'code' => $validated['code'],
            'name' => $validated['name'],
            'asset_prefix' => $validated['asset_prefix'],
            'has_basic' => true,
            'has_hardware' => $request->boolean('has_hardware'),
            'has_purchase' => $request->boolean('has_purchase'),
            'has_license_notes' => $request->boolean('has_license_notes'),
        ];

        // Check kung may nabago
        $hasChanges = false;

        foreach ($changes as $column => $value) {
            if ($device_type->{$column} != $value) {
                $hasChanges = true;
                break;
            }
        }

        if (!$hasChanges) {
            return back()->with([
                'info' => 'No changes made.',
                'edit_device_type_id' => $device_type->id,
            ]);
        }

        $device_type->update($changes);

        return back()->with('success-devicetype', 'Device type updated successfully.');
    }


    public function toggle_device_type($id)
    {
        $device_type = DeviceType::findOrFail($id);

        $device_type->update([
            'is_active' => !$device_type->is_active,
        ]);

        $message = $device_type->is_active
            ? 'Device type activated successfully.'
            : 'Device type deactivated successfully.';

        return back()->with('success', $message);
    }
}
