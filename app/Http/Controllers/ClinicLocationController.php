<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClinicLocation;
use Illuminate\Support\Facades\Validator;
use DB;

class ClinicLocationController extends Controller
{
    public function create(Request $request){

        $locatio_name = $request->locatio_name;
        $address1  = $request->address1;
        $address2 = $request->address2;
        $address3 = $request->address3;
        $address4 = $request->address4;
        $phone = $request->phone;
        $latitude = $request->latitude;
        $longitude = $request->longitude;

        $income_cate_id = $request->income_cate_id;

        $validator = Validator::make($request->all(), [
            'locatio_name' => 'required',
            'address1' => 'required',
            'address2' => 'required',
            'address3' => 'required',
            'address4' => 'required',
            'phone' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'income_cate_id' => 'required|exists:income_category,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $cliniclocation = new ClinicLocation;
        $cliniclocation->locatio_name = $locatio_name;
        $cliniclocation->address1 = $address1;
        $cliniclocation->address2 = $address2;
        $cliniclocation->address3 = $address3;
        $cliniclocation->address4 = $address4;
        $cliniclocation->phone = $phone;
        $cliniclocation->latitude = $latitude;
        $cliniclocation->longitude = $longitude;
        $cliniclocation->income_cate_id = $income_cate_id;
        $cliniclocation->save();

        return response(['success' => 'successfully added!']);
    }

    public function get(){
        $get_all = DB::table('clinic_location')
        ->join('income_category', 'clinic_location.income_cate_id', 'income_category.id')
        ->select('clinic_location.id', 'locatio_name', 'address1', 'address2', 'address3', 'address4', 'phone', 'latitude', 'longitude', 'income_cate_id', 'category_name', 'is_default')
        ->get();
        return response(['data' => $get_all]);

    }
}
