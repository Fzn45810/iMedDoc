<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DrugsDetails;
use Illuminate\Support\Facades\Validator;

class DrugDetails extends Controller
{
    public function create(Request $request){
        $drug_name = $request->drug_name;
        $dosage = $request->dosage;

        $validator = Validator::make($request->all(), [
            'drug_name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }       

        $drugsdetails = new DrugsDetails;
        $drugsdetails->drug_name = $drug_name;
        $drugsdetails->dosage = $dosage;
        $drugsdetails->save();

        return response(['success' => 'successfully create!']);
    }

    public function update(Request $request){
        $drug_details_id = $request->drug_details_id;
        $drug_name = $request->drug_name;
        $dosage = $request->dosage;

        $validator = Validator::make($request->all(), [
            'drug_details_id' => 'required|exists:drugs_details,id',
            'drug_name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        DrugsDetails::where('id', $drug_details_id)
        ->update(['drug_name' => $drug_name, 'dosage' => $dosage]);

        return response(['success' => 'successfully updated!']);
    }

    public function get_single($id){
        $get_all = DrugsDetails::where('id', $id)
        ->select('id', 'drug_name', 'dosage')
        ->first();

        return response(['data' => $get_all]);
    }

    public function get(){
        $get_all = DrugsDetails::select('id', 'drug_name', 'dosage')->get();
        return response(['data' => $get_all]);
    }
}
