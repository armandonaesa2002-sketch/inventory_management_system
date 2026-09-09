<?php

namespace App\Http\Controllers;

use App\Models\InkStock;
use Illuminate\Http\Request;

class InkStockController extends Controller
{
    public function ink_stock()
    {
        $ink_stock = InkStock::all();
        return view('inventories.ink_stock', compact('ink_stock'));
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
}
