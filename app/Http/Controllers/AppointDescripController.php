<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppointDescrip;
use Illuminate\Support\Facades\Validator;
use DB;

class AppointDescripController extends Controller
{
    public function create(Request $request){
        $appoint_description = $request->appoint_description;
        $procedures_id = $request->procedures_id;
        // Should be 1 or 0
        $color_code = $request->color_code;
        $appointments = $request->appointments;

        $validator = Validator::make($request->all(), [
            'appoint_description' => 'required',
            'procedures_id' => 'required|exists:procedures,id',
            'color_code' => 'required',
            'appointments' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $appointdescrip = new AppointDescrip;
        $appointdescrip->appoint_description = $appoint_description;
        $appointdescrip->procedures_id = $procedures_id;
        $appointdescrip->color_code = $color_code;
        $appointdescrip->appointments = $appointments;
        $appointdescrip->save();

        return response(['success' => 'successfully create!']);
    }

    public function get(){
        $get_all = DB::table('appoint_descrip')
        ->join('procedures', 'procedures.id', 'appoint_descrip.procedures_id')
        ->select('appoint_descrip.id', 'appoint_description', 'procedures.color_code', 'appointments', 'procedure_name', 'code', 'rate', 'template', 'duration_h', 'duration_m')
        ->get();
        return response(['data' => $get_all]);
    }
}
