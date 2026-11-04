<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Imports\AssetsImport;
use Maatwebsite\Excel\Facades\Excel;

class AssetImportController extends Controller
{
    public function preview(Request $request)
    {
        // 1. Validate uploaded file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        // 2. Store Excel file temporarily
        $path = $request->file('file')->store('imports');

        // 3. Read Excel file
        $import = new AssetsImport();

        Excel::import($import, Storage::path($path));

        // 4. Retrieve Excel rows
        $rows = $import->getRows();

        // 5. Display preview
        return view('assets.import-preview', compact(
            'rows',
            'path'
        ));
    }
}
