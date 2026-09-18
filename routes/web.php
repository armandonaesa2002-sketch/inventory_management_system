<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\InkStockController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/inventory/asset_tag/{type}', [InventoryController::class, 'generate_tag']);
//Mismong Page na ivivisit ni user '/inventory'
// Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
Route::get('/inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
Route::post('/inventory', [InventoryController::class, 'add_stock'])->name('inventory.add');
Route::get('/inventory/{asset}/edit', [InventoryController::class, 'edit_stock'])->name('inventory.edit');
Route::put('/inventory/{asset}/update', [InventoryController::class, 'update_stock'])->name('inventory.update');
Route::get('/inventory/{asset}/remove', [InventoryController::class, 'remove_stock'])->name('inventory.remove');
Route::delete('/inventory/{asset}/delete', [InventoryController::class, 'delete_stock'])->name('inventory.delete');
Route::get('/inventory', [InventoryController::class, 'display_index'])->name('inventory.display_index');
Route::put('/inventory/{asset}/update', [InventoryController::class, 'update_asset'])->name('inventory.update_asset');

//Assignment Route
Route::get('/inventory/assign', [AssignmentController::class, 'assign'])->name('inventory.assign');
Route::put('/inventory/assign-user', [AssignmentController::class, 'assign_user'])->name('inventory.assign_user');
//edit Assignment
Route::get('/inventory/{assignment}/edit_assignment', [AssignmentController::class, 'edit_assignment'])->name('inventory.edit_assignment');
Route::put('/inventory/{assignment}/update_assignment', [AssignmentController::class, 'update_assignment'])->name('inventory.update_assignment');
Route::get('/inventory/{assignment}/delete_assignment', [AssignmentController::class, 'delete_assignment'])->name('inventory.delete_assignment');
Route::delete('/inventory/{assignment}/remove_assignment', [AssignmentController::class, 'remove_assignment'])->name('inventory.remove_assignment');
//Return Assignment
Route::get('/inventory/{assignment}/returnpage_assignment', [AssignmentController::class, 'returnpage_assignment'])->name('inventory.returnpage_assignment');
Route::put('/inventory/{assignment}/confirm_return', [AssignmentController::class, 'confirm_return'])->name('inventory.confirm_return');
//Undo Return
Route::put('/inventory/{assignment}/undo_return', [AssignmentController::class, 'undo_return'])->name('inventory.undo_return');
//Mark as complete
Route::post('/inventory/{id}/mark_as_complete', [AssignmentController::class, 'mark_as_complete'])->name('inventory.mark_as_complete');
//Multiple Creation
Route::get('/inventory/create_multiple', [InventoryController::class, 'create_multiple'])->name('inventory.create_multiple');
Route::post('/inventory/confirm_multipleCreate', [InventoryController::class, 'confirm_multipleCreate'])->name('inventory.confirm_multipleCreate');
//View Asset
Route::get('/inventory/{asset}/view_asset', [InventoryController::class, 'view_asset'])->name('inventory.view_asset');
//Liability Form
Route::get('/inventory/{assignment}/liability_form', [AssignmentController::class, 'print_liabilityform'])->name('inventory.liability_form');
//Export Excel
Route::get('/inventory/export', [InventoryController::class, 'export'])->name('inventory.export');
//Ink Stock
Route::get('/inventory/ink_stock', [InkStockController::class, 'ink_stock'])->name('inventory.ink_stock');
Route::get('/inventory/add_ink_page', [InkStockController::class, 'add_ink_page'])->name('inventory.add_ink_page');
Route::post('/inventory/create_ink', [InkStockController::class, 'create_ink'])->name('inventory.create_ink');
Route::get('/inventory/add_multipleink', [InkStockController::class, 'add_multipleink'])->name('inventory.add_multipleink');
Route::post('/inventory/create_multipleink', [InkStockController::class, 'create_multipleink'])->name('inventory.create_multipleink');
Route::get('/inventory/{ink}/edit_ink', [InkStockController::class, 'edit_ink'])->name('inventory.edit_ink');
Route::post('/inventory/{ink}/update_ink', [InkStockController::class, 'update_ink'])->name('inventory.update_ink');











// Route::resource('inventory', 'InventoryController');
// 	Route::prefix('inventory')->as('inventory.')->group(function() {
// 		Route::prefix('/delete')->group(function() {
// 			Route::delete('/all', 'InventoryController@destroyAll')->name('destroy-all');
// 			Route::delete('/multiple', 'InventoryController@multipleDestroy')->name('multiple-destroy');
// 		});
// 	});