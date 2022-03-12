<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hospital;
use Illuminate\Support\Facades\Validator;
use DB;

class HospitalController extends Controller
{
    public function create(Request $request){
        $hospital_name = $request->hospital_name;
        $type = $request->type;
        $address1 = $request->address1;
        $address2 = $request->address2;
        $address3 = $request->address3;
        $address4 = $request->address4;
        $phone = $request->phone;
        $fax = $request->fax;
        // type should be email
        $hospital_email = $request->hospital_email;
        $website = $request->website;
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $forms_id = $request->forms_id;
        $income_category_id = $request->income_category_id;

        $validator = Validator::make($request->all(), [
            'hospital_name' => 'required',
            'type' => 'required',
            'address1' => 'required',
            'address2' => 'required',
            'address3' => 'required',
            'address4' => 'required',
            'phone' => 'required',
            'fax' => 'required',
            'hospital_email' => 'email|required',
            'website' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'forms_id' => 'required|exists:form,id',
            'income_category_id' => 'required|exists:income_category,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $hospital = new Hospital;
        $hospital->hospital_name = $hospital_name;
        $hospital->type = $type;
        $hospital->address1 = $address1;
        $hospital->address2 = $address2;
        $hospital->address3 = $address3;
        $hospital->address4 = $address4;
        $hospital->phone = $phone;
        $hospital->fax = $fax;
        $hospital->hospital_email = $hospital_email;
        $hospital->website = $website;
        $hospital->latitude = $latitude;
        $hospital->longitude = $longitude;
        $hospital->forms_id = $forms_id;
        $hospital->income_category_id = $income_category_id;
        $hospital->save();

        return response(['success' => 'successfully create!']);
    }

    public function get(){
        $get_all = DB::table('hospital')
        ->join('form', 'hospital.forms_id', 'form.id')
        ->join('income_category', 'hospital.income_category_id', 'income_category.id')
        ->get();
        return response(['data' => $get_all]);
    }
}
