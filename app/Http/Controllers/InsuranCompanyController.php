<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InsuranCompany;
use Illuminate\Support\Facades\Validator;
use DB;

class InsuranCompanyController extends Controller
{
    public function create(Request $request){
        $insur_company_name = $request->insur_company_name;
        $address1 = $request->address1;
        $address2 = $request->address2;
        $address3 = $request->address3;
        $address4 = $request->address4;
        $phone = $request->phone;
        $insurance_form_name = $request->insurance_form_name;
        $mode_of_paymen = $request->mode_of_paymen;
        // Should be 1 or 0
        $deduct_tax = $request->deduct_tax;

        $validator = Validator::make($request->all(), [
            'insur_company_name' => 'required',
            'address1' => 'required',
            'address2' => 'required',
            'address3' => 'required',
            'address4' => 'required',
            'phone' => 'required',
            'insurance_form_name' => 'required',
            'mode_of_paymen' => 'required',
            'deduct_tax' => 'boolean|required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $insurancompany = new InsuranCompany;
        $insurancompany->insur_company_name = $insur_company_name;
        $insurancompany->address1 = $address1;
        $insurancompany->address2 = $address2;
        $insurancompany->address3 = $address3;
        $insurancompany->address4 = $address4;
        $insurancompany->phone = $phone;
        $insurancompany->insurance_form_name = $insurance_form_name;
        $insurancompany->mode_of_paymen = $mode_of_paymen;
        $insurancompany->deduct_tax = $deduct_tax;
        $insurancompany->save();

        return response(['success' => 'successfully create!']);
    }

    public function get(){
        $get_all = InsuranCompany::get();

        return response(['data' => $get_all]);
    }
}
