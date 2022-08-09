<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppointDescrip;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Imports\ImportAppointDescrip;
use Excel;

class AppointDescripController extends Controller
{
    public function create(Request $request){
        $appoint_description = $request->appoint_description;
        $procedures_id = $request->procedures_id;
        // Should be 1 or 0
        $color_code = $request->color_code;
        $appointments = $request->appointments;

        $validator = Validator::make($request->all(), [
            'appoint_description' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        if(!is_null($procedures_id)){
            $validator = Validator::make($request->all(), [
                'procedures_id' => 'required|exists:procedures,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }
        }

        $appointdescrip = new AppointDescrip;
        $appointdescrip->appoint_description = $appoint_description;
        $appointdescrip->procedures_id = $procedures_id;
        $appointdescrip->color_code = $color_code;
        $appointdescrip->appointments = $appointments;
        $appointdescrip->save();

        return response(['success' => 'successfully create!']);
    }

    public function update(Request $request){
        $appoint_description_id = $request->appoint_description_id;
        $appoint_description = $request->appoint_description;
        $procedures_id = $request->procedures_id;
        // Should be 1 or 0
        $color_code = $request->color_code;
        $appointments = $request->appointments;

        $validator = Validator::make($request->all(), [
            'appoint_description_id' => 'required|exists:appoint_descrip,id',
            'appoint_description' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        if(!is_null($procedures_id)){
            $validator = Validator::make($request->all(), [
                'procedures_id' => 'required|exists:procedures,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }
        }

        AppointDescrip::where('id', $appoint_description_id)
        ->update(['appoint_description' => $appoint_description, 'procedures_id' => $procedures_id, 'color_code' => $color_code, 'appointments' => $appointments
        ]);

        return response(['success' => 'successfully updated!']);
    }

    public function get_single($id){
        $get_all = DB::table('appoint_descrip')
        ->where('appoint_descrip.id', $id)
        ->leftjoin('procedures', 'procedures.id', 'appoint_descrip.procedures_id')
        ->select('appoint_descrip.id', 'appoint_description', 'procedures.color_code', 'appointments', 'procedure_name', 'code', 'rate', 'template', 'duration_h', 'duration_m')
        ->get();
        return response(['data' => $get_all]);
    }

    public function get(){
        $get_all = DB::table('appoint_descrip')
        ->leftjoin('procedures', 'procedures.id', 'appoint_descrip.procedures_id')
        ->select('appoint_descrip.id', 'appoint_description', 'procedures.color_code', 'appointments', 'procedure_name', 'code', 'rate', 'template', 'duration_h', 'duration_m')
        ->get();
        return response(['data' => $get_all]);
    }

    public function import_appointdec(Request $request){
        $extention = $request->file("importfile")->getClientOriginalExtension();
        if($extention == 'xlsx' || $extention == 'csv' || $extention == 'XLSX' || $extention == 'CSV'){

            $import_file = $request->file("importfile");
            Excel::import(new ImportAppointDescrip, $import_file);
            return response(['success' => 'successfully imported!']);
            
        }else{
            return response(['message' => 'file should be xlsx or csv!']);
        }
    }
}
