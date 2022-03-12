<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InsurancePlane;
use Illuminate\Support\Facades\Validator;
use DB;

class InsurancePlaneController extends Controller
{
    public function create(Request $request){
        $insurance_plan_name = $request->insurance_plan_name;
        $insurance_comp_id = $request->insurance_comp_id;

        $validator = Validator::make($request->all(), [
            'insurance_plan_name' => 'required',
            'insurance_comp_id' => 'required|exists:insurance_company,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $insuranceplane = new InsurancePlane;
        $insuranceplane->insurance_plane_name = $insurance_plan_name;
        $insuranceplane->insurance_comp_id = $insurance_comp_id;
        $insuranceplane->save();

        return response(['success' => 'successfully create!']);
    }

    public function update(Request $request){
        $plan_id = $request->plan_id;
        $insurance_plan_name = $request->insurance_plan_name;
        $insurance_comp_id = $request->insurance_comp_id;

        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:insurance_plane,id',
            'insurance_plan_name' => 'required',
            'insurance_comp_id' => 'required|exists:insurance_company,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        InsurancePlane::where('id', $plan_id)->update(['insurance_plane_name' => $insurance_plan_name, 'insurance_comp_id' => $insurance_comp_id]);

        return response(['success' => 'successfully updated!']);
    }

    public function get(){
        $get_all = DB::table('insurance_plane')
        ->join('insurance_company', 'insurance_company.id', 'insurance_plane.insurance_comp_id')
        ->select('insurance_plane.id', 'insurance_plane_name', 'insur_company_name')
        ->get();
        return response(['data' => $get_all]);
    }

    public function get_single($id){
        $get_all = DB::table('insurance_plane')
        ->join('insurance_company', 'insurance_company.id', 'insurance_plane.insurance_comp_id')
        ->select('insurance_plane.id', 'insurance_plane_name', 'insur_company_name')
        ->where('insurance_plane.id', $id)
        ->get();
        return response(['data' => $get_all]);
    }
}
