<?php

namespace App\Http\Controllers;

use App\Models\InkStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InkStockController extends Controller
{
    public function ink_stock()
    {
        $ink_stock = InkStock::all();
        $total_ink = InkStock::count();
        $total_instock = InkStock::where('status', 'In Stock')->count();
        $total_lowstock = InkStock::where('status', 'Low Stock')->count();
        $total_outofstock = InkStock::where('status', 'Out of Stock')->count();

        $data = array_merge(compact('ink_stock', 'total_ink', 'total_instock', 'total_lowstock', 'total_outofstock'));

        return view('inventories.ink_stock', compact('data'));
    }
    public function add_ink_page()
    {
        return view('inventories.add_ink');
    }
    public function create_ink(Request $request)
    {
        // dd($request);

        $brand = $request->ink_brand === "other" ? $request->other_brand : $request->ink_brand;
        $type = $request->ink_type === "other" ? $request->ink_type_other : $request->ink_type;
        $color = $request->color === "other" ? $request->color_other : $request->color;
        $ink_check = InkStock::where('brand', $brand)->where('type', $type)->where('color', $color)->exists();
        if ($ink_check) {
            return back()->withInput()->with('duplicate_ink', 'Duplicate Ink Entry');
        }
        if ($request->ink_stock == 0) {
            $status = "Out of Stock";
        } elseif ($request->ink_stock > $request->reorder_level) {
            $status = "In Stock";
        } else {
            $status = "Low Stock";
        }
        // dd($brand, $type, $color, $status, $request->reorder_level);
        $ink_stock = InkStock::create([
            'brand' => $brand,
            'type' => $type,
            'color' => $color,
            'stock' => $request->ink_stock,
            'reorder_level' => $request->reorder_level,
            'status' => $status,
            'in' => $request->ink_stock,
            'out' => 0
        ]);
        return redirect(route('inventory.ink_stock'))->with('success', 'Ink Successfully Added');
    }

    public function add_multipleink()
    {
        return view('inventories.add_multipleink');
    }
    public function create_multipleink(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate(
            [
                'groups' => ['required', 'array', 'min:1'],

                'groups.*.brand' => ['required'],
                'groups.*.type' => ['required'],
                'groups.*.reorder_level' => ['required', 'integer', 'min:1'],

                'groups.*.colors' => ['nullable', 'array', 'min:1'],
                'groups.*.colors.*.selected' => ['required'],
                'groups.*.colors.*.stock' => ['required', 'integer', 'min:0'],

                'groups.*.custom_colors' => ['nullable', 'array'],
                'groups.*.custom_colors.*.name' => ['required'],
                'groups.*.custom_colors.*.stock' => ['required', 'integer', 'min:0'],
            ]

        );
        foreach ($validated['groups'] as $index => $group) {

            $hasDefaultColors = !empty($group['colors']);
            $hasCustomColors = !empty($group['custom_colors']);

            if (!$hasDefaultColors && !$hasCustomColors) {
                return back()
                    ->withErrors([
                        'color_error' => 'Please select at least one color or add a custom color.'
                    ])
                    ->withInput();
            }
        }
        // dd($validated);

        $seenGroups = [];
        $seenColors = [];
        $duplicates = [];

        foreach ($validated['groups'] as $index => $group) {

            $brand = $group['brand'] === 'other'
                ? $group['other_brand']
                : $group['brand'];

            $type = $group['type'] === 'other'
                ? $group['other_type']
                : $group['type'];


            /*
    |--------------------------------------------------------------------------
    | CHECK DUPLICATE GROUP
    | brand + type only
    |--------------------------------------------------------------------------
    */

            $groupKey = strtolower(trim($brand))
                . '|' . strtolower(trim($type));

            if (isset($seenGroups[$groupKey])) {

                $duplicates[] =
                    "Duplicate Group: {$brand} - {$type}";
            }

            $seenGroups[$groupKey] = true;


            /*
    |--------------------------------------------------------------------------
    | STANDARD COLORS
    |--------------------------------------------------------------------------
    */

            foreach ($group['colors'] ?? [] as $color) {

                $colorName = $color['selected'];

                $colorKey = strtolower(trim($brand))
                    . '|' . strtolower(trim($type))
                    . '|' . strtolower(trim($colorName));


                /*
        |--------------------------------------------------------------------------
        | DUPLICATE COLOR WITHIN SUBMISSION
        |--------------------------------------------------------------------------
        */

                if (isset($seenColors[$colorKey])) {

                    $duplicates[] =
                        "Duplicate Color: {$brand} - {$type} - {$colorName}";
                }

                $seenColors[$colorKey] = true;


                /*
        |--------------------------------------------------------------------------
        | CHECK EXISTING DB
        | brand + type + color
        |--------------------------------------------------------------------------
        */

                $exists = InkStock::where('brand', $brand)
                    ->where('type', $type)
                    ->where('color', $colorName)
                    ->exists();

                if ($exists) {

                    $duplicates[] =
                        "Already Exists: {$brand} - {$type} - {$colorName}";
                }
            }


            /*
    |--------------------------------------------------------------------------
    | CUSTOM / ADDITIONAL COLORS
    |--------------------------------------------------------------------------
    */

            foreach ($group['custom_colors'] ?? [] as $color) {

                $colorName = $color['name'];

                $colorKey = strtolower(trim($brand))
                    . '|' . strtolower(trim($type))
                    . '|' . strtolower(trim($colorName));


                /*
        |--------------------------------------------------------------------------
        | DUPLICATE COLOR WITHIN SUBMISSION
        |--------------------------------------------------------------------------
        */

                if (isset($seenColors[$colorKey])) {

                    $duplicates[] =
                        "Duplicate Color: {$brand} - {$type} - {$colorName}";
                }

                $seenColors[$colorKey] = true;


                /*
        |--------------------------------------------------------------------------
        | CHECK EXISTING DB
        | brand + type + color
        |--------------------------------------------------------------------------
        */

                $exists = InkStock::where('brand', $brand)
                    ->where('type', $type)
                    ->where('color', $colorName)
                    ->exists();

                if ($exists) {

                    $duplicates[] =
                        "Already Exists: {$brand} - {$type} - {$colorName}";
                }
            }
        }


        /*
|--------------------------------------------------------------------------
| SHOW ALL DUPLICATES
|--------------------------------------------------------------------------
*/

        if (!empty($duplicates)) {

            return back()
                ->withErrors([
                    'duplicate_color' => $duplicates,
                ])
                ->withInput();
        }

        // dd("walang error.");



        DB::transaction(function () use ($validated) {

            foreach ($validated['groups'] as $group) {

                $brand = $group['brand'] === 'other'
                    ? $group['other_brand']
                    : $group['brand'];

                $type = $group['type'] === 'other'
                    ? $group['other_type']
                    : $group['type'];


                /*
        |--------------------------------------------------------------------------
        | STANDARD COLORS
        |--------------------------------------------------------------------------
        */

                foreach ($group['colors'] ?? [] as $color) {

                    $colorName = $color['selected'];
                    $stock = $color['stock'];

                    if ($stock == 0) {
                        $status = "Out of Stock";
                    } elseif ($stock > $group['reorder_level']) {
                        $status = "In Stock";
                    } else {
                        $status = "Low Stock";
                    }

                    InkStock::create([
                        'brand' => $brand,
                        'type' => $type,
                        'color' => $colorName,
                        'stock' => $stock,
                        'reorder_level' => $group['reorder_level'],
                        'status' => $status,
                        'in' => $stock,
                        'out' => 0,
                    ]);
                }


                /*
        |--------------------------------------------------------------------------
        | CUSTOM / ADDITIONAL COLORS
        |--------------------------------------------------------------------------
        */

                foreach ($group['custom_colors'] ?? [] as $color) {

                    $colorName = $color['name'];
                    $stock = $color['stock'];

                    if ($stock == 0) {
                        $status = "Out of Stock";
                    } elseif ($stock > $group['reorder_level']) {
                        $status = "In Stock";
                    } else {
                        $status = "Low Stock";
                    }

                    InkStock::create([
                        'brand' => $brand,
                        'type' => $type,
                        'color' => $colorName,
                        'stock' => $stock,
                        'reorder_level' => $group['reorder_level'],
                        'status' => $status,
                        'in' => $stock,
                        'out' => 0,
                    ]);
                }
            }
        });

        return redirect(route('inventory.ink_stock'))
            ->with('success', 'Ink Successfully Added');
    }
}
