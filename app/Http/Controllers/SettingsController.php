<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\PreparedBy;
use App\Models\DeviceType;
use App\Models\Department;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make($request->all(), [
            'code' => ['required', 'string', 'max:50', 'unique:device_types,code'],
            'name' => ['required', 'string', 'max:100', 'unique:device_types,name'],
            'asset_prefix' => ['required', 'string', 'max:20'],
            'has_basic' => ['required', 'boolean'],
            'has_hardware' => ['sometimes', 'boolean'],
            'has_purchase' => ['sometimes', 'boolean'],
            'has_license_notes' => ['sometimes', 'boolean'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'devicetype')
                ->withInput()
                ->with('device_type_form_mode', 'add_device_type');
        }
        $validated = $validator->validated();
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
        $validator = Validator::make($request->all(), [
            'code' => ['required', 'string', 'max:50', Rule::unique('device_types', 'code')->ignore($device_type->id)],
            'name' => ['required', 'string', 'max:100', Rule::unique('device_types', 'name')->ignore($device_type->id)],
            'asset_prefix' => ['required', 'string', 'max:20'],
            'has_basic' => ['required', 'boolean'],
            'has_hardware' => ['sometimes', 'boolean'],
            'has_purchase' => ['sometimes', 'boolean'],
            'has_license_notes' => ['sometimes', 'boolean'],
        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'devicetype')
                ->withInput()
                ->with('edit_device_type_id', $device_type->id);
        }
        $validated = $validator->validated();
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
                'device_info' => 'No changes made.',
                'edit_device_type_id' => $device_type->id,
            ]);
        }

        $device_type->update($changes);

        return back()->with('success-devicetype', 'Device type updated successfully.');
    }

    //DEACTIVATE AND ACTIVATE DEVICE TYPE
    public function toggle_device_type($id)
    {
        $device_type = DeviceType::findOrFail($id);

        $device_type->update([
            'is_active' => !$device_type->is_active,
        ]);

        $message = $device_type->is_active
            ? 'Device type activated successfully.'
            : 'Device type deactivated successfully.';

        return back()->with('success-devicetype', $message);
    }

    public function add_department(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'dept_code' => ['required', 'string', 'max:50', 'unique:departments,code'],
            'dept_name' => ['required', 'string', 'max:100', 'unique:departments,name'],
        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'department')
                ->withInput()
                ->with('department_form_mode', 'add');
        }

        $validated = $validator->validated();

        Department::create([
            'code' => $validated['dept_code'],
            'name' => $validated['dept_name'],
        ]);

        return back()->with('success-department', 'Department added successfully.');
    }

    public function update_department(Department $department, Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'dept_code' => ['required', 'string', 'max:50', Rule::unique('departments', 'code')->ignore($department->id),],
            'dept_name' => ['required', 'string', 'max:100', Rule::unique('departments', 'name')->ignore($department->id),],
        ]);
        // Validation errors, including duplicate code/name
        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'department')
                ->withInput()
                ->with('edit_department_id', $department->id);
        }
        $validated = $validator->validated();

        $changes = [
            'code' => $validated['dept_code'],
            'name' => $validated['dept_name'],
        ];

        // Check kung may nabago
        $hasChanges = false;

        foreach ($changes as $column => $value) {
            if ($department->{$column} != $value) {
                $hasChanges = true;
                break;
            }
        }

        if (!$hasChanges) {
            return back()
                ->with('info', 'No changes made.')
                ->with('edit_department_id', $department->id);
        }

        $department->update($changes);

        return back()->with('success-department', 'Department updated successfully.');
    }

    //DEACTIVATE AND ACTIVATE DEPARTMENT
    public function toggle_department($id)
    {
        $department = Department::findOrFail($id);

        $department->update([
            'is_active' => !$department->is_active,
        ]);

        $message = $department->is_active
            ? 'Department activated successfully.'
            : 'Department deactivated successfully.';

        return back()->with('success-department', $message);
    }

    public function add_preparedby(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'prep_code' => ['required', 'string', 'max:50', 'unique:prepared_by_options,code'],
            'prep_name' => ['required', 'string', 'max:100', 'unique:prepared_by_options,name'],
        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'preparedby')
                ->withInput()
                ->with('preparedby_form_mode', 'add_preparedby');
        }

        $validated = $validator->validated();

        PreparedBy::create([
            'code' => $validated['prep_code'],
            'name' => $validated['prep_name'],
        ]);

        return back()->with('success-preparedby', 'Department added successfully.');
    }

    public function update_preparedby(PreparedBy $preparedby, Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'prep_code' => ['required', 'string', 'max:50', Rule::unique('prepared_by_options', 'code')->ignore($preparedby->id),],
            'prep_name' => ['required', 'string', 'max:100', Rule::unique('prepared_by_options', 'name')->ignore($preparedby->id),],
        ]);
        // Validation errors, including duplicate code/name
        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'preparedby')
                ->withInput()
                ->with('edit_preparedby_id', $preparedby->id);
        }
        $validated = $validator->validated();

        $changes = [
            'code' => $validated['prep_code'],
            'name' => $validated['prep_name'],
        ];

        // Check kung may nabago
        $hasChanges = false;

        foreach ($changes as $column => $value) {
            if ($preparedby->{$column} != $value) {
                $hasChanges = true;
                break;
            }
        }

        if (!$hasChanges) {
            return back()
                ->with('info-preparedby', 'No changes made.')
                ->with('edit_preparedby_id', $preparedby->id);
        }

        $preparedby->update($changes);

        return back()->with('success-preparedby', 'Name updated successfully.');
    }
    //DEACTIVE AND ACTIVATE PREPAREDBY
    public function toggle_preparedby($id)
    {
        $preparedby = PreparedBy::findOrFail($id);

        $preparedby->update([
            'is_active' => !$preparedby->is_active,
        ]);

        $message = $preparedby->is_active
            ? 'Name activated successfully.'
            : 'Name deactivated successfully.';

        return back()->with('success-preparedby', $message);
    }
}
