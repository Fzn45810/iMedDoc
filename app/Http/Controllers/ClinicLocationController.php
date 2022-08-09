<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClinicLocation;
use Illuminate\Support\Facades\Validator;
use App\Imports\ImportClinicLocation;
use Excel;
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
            'locatio_name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        if(!is_null($income_cate_id)){
            $validator = Validator::make($request->all(), [
                'income_cate_id' => 'required|exists:income_category,id'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }
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

        return response(['success' => 'successfully create!']);
    }

    public function update(Request $request){
        $clinic_location_id = $request->clinic_location_id;
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
            'clinic_location_id' => 'required|exists:clinic_location,id',
            'locatio_name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        if(!is_null($income_cate_id)){
            $validator = Validator::make($request->all(), [
                'income_cate_id' => 'required|exists:income_category,id'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }
        }

        ClinicLocation::where('id', $clinic_location_id)->update(['locatio_name' => $locatio_name, 'address1' => $address1, 'address2' => $address2, 'address3' => $address3, 'address4' => $address4, 'phone' => $phone, 'latitude' => $latitude, 'longitude' => $longitude, 'income_cate_id' => $income_cate_id]);

        return response(['success' => 'successfully updated!']);
    }

    public function get_single($id){
        $get_all = DB::table('clinic_location')
        ->leftjoin('income_category', 'clinic_location.income_cate_id', 'income_category.id')
        ->select('clinic_location.id', 'locatio_name', 'address1', 'address2', 'address3', 'address4', 'phone', 'latitude', 'longitude', 'income_cate_id', 'category_name', 'is_default')
        ->where('clinic_location.id', $id)
        ->first();
        return response(['data' => $get_all]);
    }

    public function get(){
        $get_all = DB::table('clinic_location')
        ->leftjoin('income_category', 'clinic_location.income_cate_id', 'income_category.id')
        ->select('clinic_location.id', 'locatio_name', 'address1', 'address2', 'address3', 'address4', 'phone', 'latitude', 'longitude', 'income_cate_id', 'category_name', 'is_default')
        ->get();
        return response(['data' => $get_all]);
    }

    public function import_cliniclocation(Request $request){
        $extention = $request->file("importfile")->getClientOriginalExtension();
        if($extention == 'xlsx' || $extention == 'csv' || $extention == 'XLSX' || $extention == 'CSV'){

            $import_file = $request->file("importfile");
            Excel::import(new ImportClinicLocation, $import_file);
            return response(['success' => 'successfully imported!']);
            
        }else{
            return response(['message' => 'file should be xlsx or csv!']);
        }
    }
}
