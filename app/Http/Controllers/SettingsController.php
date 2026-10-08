<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\PreparedBy;
use App\Models\DeviceType;
use App\Models\Department;

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
}
