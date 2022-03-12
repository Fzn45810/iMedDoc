<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppointType;
use Illuminate\Support\Facades\Validator;
use DB;

class AppontTypeController extends Controller
{
    public function create(Request $request){
        $appoint_name = $request->appoint_name;

        $validator = Validator::make($request->all(), [
            'appoint_name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $appoint_type = new AppointType;
        $appoint_type->appoint_name = $appoint_name;
        $appoint_type->save();

        return response(['success' => 'successfully create!']);
    }

    public function get(){
        $get_all = AppointType::get();
        return response(['data' => $get_all]);

    }
}
