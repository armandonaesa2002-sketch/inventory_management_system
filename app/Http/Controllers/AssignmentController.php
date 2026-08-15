<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Assignment;
use App\Models\RepairHistory;
use PhpOffice\PhpWord\TemplateProcessor;

class AssignmentController extends Controller
{
    public function assign(){

        $assets = Asset::where('status', 'Available')->get();
        return view('inventories.assign', compact('assets'));
    }
    public function assign_user(Request $request){
        
        DB::transaction(function() use ($request){
            $assignment = Assignment::create([
                'asset_id'=> $request->asset_id,
                'user_name'=> $request->user_name,
                'department'=> $request->department,
                'location'=> $request->location,
                'designation'=> $request->designation,
                'inclusion'=> $request->inclusion,
                'prepared_by'=> $request->prepared_by
                ]);
                // dd($assignment);
            
            $target_asset = Asset::where('id', $request->asset_id)->first();
            $target_asset->status = 'Assigned';
            $target_asset->save();   
        });
        return redirect(route('inventory.display_index'))->with('success-assignment', 'User Successfully assigned');
    }

    public function edit_assignment(Assignment $assignment){
        return view('inventories.edit_assignment', ['assignment' => $assignment]);
    }
    public function update_assignment(Assignment $assignment, Request $request){
        $assignment->fill($request->input('assignment'));
        if(!$assignment->isDirty()){
            return back()->with('info', 'No change made');
        } 
        $assignment->save();
        return redirect(route('inventory.display_index'))->with('success-assignment', 'Successfully Updated');
    }
    public function delete_assignment(Assignment $assignment){
        return view('inventories.delete_assignment', ['assignment' => $assignment]);
    }
    public function remove_assignment(Assignment $assignment){
        
        DB::transaction(function() use($assignment){

            $target_asset = Asset::find($assignment->asset_id);
            $target_asset->status = 'Available';
            $target_asset->save();
            
            $assignment->delete();
        });
        return redirect(route('inventory.display_index'))->with('success-assignment', 'Successfully Deleted');   
    }

    public function returnpage_assignment(Assignment $assignment){
        return view('inventories.return', ['assignment' => $assignment]);
    }
    public function confirm_return(Assignment $assignment, Request $request){
    
        $assignment->fill($request->input('return'));
        $target_asset = Asset::find($assignment->asset_id);
        $return_status = $assignment->status;
        // $target_assignment = Assignment::find($assignment->id);

        DB::transaction(function() use($assignment, $request, $target_asset, $return_status){

            if($assignment->status === "For Repair"){
                $repairhistory = RepairHistory::create([
                    'asset_id' => $target_asset->id,
                    'assignment_id' => $assignment->id,
                    'user_name' => $assignment->user_name,
                    'asset_tag' => $target_asset->asset_tag,
                    'type' => $request->input('return.status'),
                    'repair_status' => 'In progress',
                    'description' => $request->input('return.remarks'),
                    'previous_remarks' => $request->input('return.remarks')
                ]);
            }

            if($assignment->status == "Spare"){
                $target_asset->status = 'Available';
                $assignment->status = 'Returned';
            } else {
                $target_asset->status = $return_status;
                $assignment->status = $return_status;
            }
            $target_asset->save();
            $assignment->save();
                    
        });
            
        return redirect(route('inventory.display_index'))->with('success-assignment', 'Successfully Updated');

    }
    public function undo_return(Assignment $assignment){

        $repair_id = RepairHistory::where('asset_id', $assignment->asset_id)
        ->where('assignment_id', $assignment->id)
        ->latest('created_at')
        ->first();
        // dd($repair_id);
        
        DB::transaction(function() use($assignment, $repair_id){
            if($repair_id){
                if($repair_id->repair_status_after === "repaired") {
                    $assignment->update([
                    'status' => $repair_id->type,
                    'remarks' => $repair_id->previous_remarks
                    ]);
                    $assignment->asset->update([
                        'status' => $repair_id->type
                    ]);
                    $repair_id->update([
                        'repair_status' => 'In progress',
                        'return_outcome' => '',
                        'repair_status_after' => ''
                    ]); 
                } else {
                    $assignment->update([
                        'status' => 'In Use',
                        'remarks' => '',
                    ]);

                    $assignment->asset->update([
                        'status' => 'Assigned',
                    ]);
                        $repair_id->update([
                            'repair_status' => 'Cancelled',
                            'description' => $repair_id->description . ' | Undo Repair'
                            ]);
                    }
            } else {
                $assignment->update([
                        'status' => 'In Use',
                        'remarks' => '',
                    ]);

                    $assignment->asset->update([
                        'status' => 'Assigned',
                    ]);
            }
        });

        return back()->with('success-assignment', 'Action has been undone.');
    }

    public function mark_as_complete(Request $request, $id) {
        $assignment = Assignment::findOrFail($id);
        $option = $request->assignment_option;
        
        $repair_id = RepairHistory::where('asset_id', $assignment->asset_id)
                ->latest('created_at')
                ->first();

        DB::transaction(function() use($repair_id, $option, $assignment){
            if($repair_id){
                $repair_id->update([
                    'repair_status' => 'Completed',
                    'return_outcome' => $option,
                    'repair_status_after' => 'repaired'
                    // 'previous_assignment_status' => $assignment->status,
                    // 'previous_asset_status' => $assignment->asset->status
                ]);
            }

            if($option === "keep"){
                $assignment->update([
                    'status' => 'In Use',
                    'remarks' => ''
                ]);
                $assignment->asset->update([
                    'status' => 'Assigned'
                ]);
            }
            if($option === "spare"){
                $assignment->update([
                    'status' => 'Returned',
                    'remarks' => ''
                ]);
                $assignment->asset->update([
                    'status' => 'Available'
                ]);
            }
        });
        
        return back()->with('success-assignment', 'Successfully Mark as Complete');
    }

    public function print_liabilityform(Assignment $assignment)
    {
        $assignment->load(['asset.hardware','asset.software']);

        $asset = $assignment->asset;
        
        $assignment_count = Assignment::where('asset_id', $asset->id)->count();
        $remarks = $assignment_count === 1 ? 'Brand New' : 'Reissued';


        $template = new TemplateProcessor(
            storage_path('app/templates/liability_form.docx')
        );

        $label = "SERIAL NO.";
        $serial = $asset->serial_number;
        if($asset->device_type === "desktop"){
            $label = "PRODUCT KEY";
            $serial = $asset->software->product_key_os;
        }
        $combined_description = $asset->item_description;
        if ($assignment->inclusion) {
            $combined_description .= ', ' . $assignment->inclusion;
        }

        $template->setValue('label', $label);
        $template->setValue('item_description', $combined_description);
        $template->setValue('user_name', $assignment->user_name);
        $template->setValue('department', $assignment->department);
        $template->setValue('designation', $assignment->designation);
        $template->setValue('remarks', $remarks);
        $template->setValue('it_name', $assignment->prepared_by);
        $template->setValue('serial_number', $serial);
        $template->setValue('created_at', $assignment->created_at->format('m/d/Y'));

        $fileName = 'Liability_Form_' .
            $asset->asset_tag . '_' .
            str_replace(' ', '_', $assignment->user_name) . '_' .
            $assignment->id .
            '.docx';
        $outputPath = storage_path(
            'app/templates/'. $fileName
        );

        $template->saveAs($outputPath);

        return response()
                ->download($outputPath, $fileName)
                ->deleteFileAfterSend(true);
        

    }

}
