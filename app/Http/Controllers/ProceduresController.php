<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Procedures;
use App\Models\InsuranceProcedure;
use App\Models\InsuranCompany;
use Illuminate\Support\Facades\Validator;
use App\Imports\ImportProcedures;
use Excel;
use DB;

class ProceduresController extends Controller
{
    public function create(Request $request){
        $procedure_name = $request->procedure_name;
        $code = $request->code;
        $rate = $request->rate;
        $template = $request->template;
        $color_code = $request->color_code;
        $duration_h = $request->duration_h;
        $duration_m = $request->duration_m;
        $insu_comp_id = $request->insu_comp_id;

        $validator = Validator::make($request->all(), [
            'procedure_name' => 'required',
            'code' => 'required',
            'rate' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        if(!is_null($duration_h)){
            $validator = Validator::make($request->all(), [
                'duration_h' => 'integer|required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }
        }

        if(!is_null($duration_m)){
            $validator = Validator::make($request->all(), [
                'duration_m' => 'integer|required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }
        }

        if(!is_null($insu_comp_id)){
            foreach ($insu_comp_id as $key => $value) {
                if(!is_null($value['id'])){
                    $insurance = InsuranCompany::where('id', $value['id'])->first();
                    if(is_null($insurance)){
                        return response(['error' => 'Insurance Company '. $value['id']  .' not found.']);
                    }
                }else{
                    unset($insu_comp_id[$key]);
                }
            }
        }

        $procedures = new Procedures;
        $procedures->procedure_name = $procedure_name;
        $procedures->code = $code;
        $procedures->rate = $rate;
        $procedures->template = $template;
        $procedures->color_code = $color_code;
        $procedures->duration_h = $duration_h;
        $procedures->duration_m = $duration_m;
        $procedures->save();

        if(!is_null($insu_comp_id)){
            foreach ($insu_comp_id as $key => $value) {
                $insuprocedure = new InsuranceProcedure;
                $insuprocedure->procedures_id = $procedures->id;
                $insuprocedure->insurance_id = $value['id'];
                $insuprocedure->rates = $value['rate'];
                $insuprocedure->save();
            }
        }

        return response(['success' => 'successfully create!']);
    }

    public function update(Request $request){
        $procedure_id = $request->procedure_id;
        $procedure_name = $request->procedure_name;
        $code = $request->code;
        $rate = $request->rate;
        $template = $request->template;
        $color_code = $request->color_code;
        $duration_h = $request->duration_h;
        $duration_m = $request->duration_m;
        $insu_comp_id = $request->insu_comp_id;

        $validator = Validator::make($request->all(), [
            'procedure_id' => 'required|exists:procedures,id',
            'procedure_name' => 'required',
            'code' => 'required',
            'rate' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        if(!is_null($duration_h)){
            $validator = Validator::make($request->all(), [
                'duration_h' => 'integer|required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }
        }

        if(!is_null($duration_m)){
            $validator = Validator::make($request->all(), [
                'duration_m' => 'integer|required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }
        }

        if(!is_null($insu_comp_id)){
            foreach ($insu_comp_id as $key => $value) {
                if(!is_null($value['id'])){
                    $insurance = InsuranCompany::where('id', $value['id'])->first();
                    if(is_null($insurance)){
                        return response(['error' => 'Insurance Company '. $value['id']  .' not found.']);
                    }
                }else{
                    unset($insu_comp_id[$key]);
                }
            }
        }

        Procedures::where('id', $procedure_id)
        ->update(['procedure_name' => $procedure_name, 'code' => $code, 'rate' => $rate, 'template' => $template,
            'color_code' => $color_code, 'duration_h' => $duration_h, 'duration_m' => $duration_m
        ]);

        InsuranceProcedure::where('procedures_id', $procedure_id)->delete();

        if(!is_null($insu_comp_id)){
            foreach ($insu_comp_id as $key => $value) {
                $insuprocedure = new InsuranceProcedure;
                $insuprocedure->procedures_id = $procedure_id;
                $insuprocedure->insurance_id = $value['id'];
                $insuprocedure->rates = $value['rate'];
                $insuprocedure->save();
            }
        }

        return response(['success' => 'successfully updated!']);
    }

    public function get(){
        $get_all = DB::table('procedures')
        ->select('procedures.id', 'procedure_name','color_code', 'code', 'rate')
        ->orderBy('created_at')
        ->get();

        return response(['data' => $get_all]);
    }

    public function get_single($id){
        $get_all = DB::table('procedures')
        ->leftjoin('insurance_proced_relat', 'insurance_proced_relat.procedures_id', 'procedures.id')
        ->leftjoin('insurance_company', 'insurance_proced_relat.insurance_id', 'insurance_company.id')
        ->select('insurance_proced_relat.id', 'insurance_proced_relat.procedures_id', 'insurance_proced_relat.insurance_id', 'procedure_name', 'code', 'rate', 'template', 'color_code', 'duration_h', 'duration_m', 'insur_company_name', 'insurance_proced_relat.rates')
        ->where('procedures.id', $id)
        ->get();

        $excelArray = [];
        $tempPatientId = null;
        $insurance = [];

        foreach($get_all as $key => $value){
            if($tempPatientId == null){
                $object = new \stdClass();
                $object->procedures_id = $value->procedures_id;
                $object->procedure_name = $value->procedure_name;
                $object->code = $value->code;
                $object->rate = $value->rate;
                $object->template = $value->template;
                $object->color_code = $value->color_code;
                $object->duration_h = $value->duration_h;
                $object->duration_m = $value->duration_m;
                $insurance [] = ['insuranceID' => $value->insurance_id, 'insurance_company' => $value->insur_company_name, 'rates' => $value->rates];
                $object->insurance = $insurance;

                $tempPatientId = $value->procedures_id;
                if((count($get_all)-1) == $key){
                    $excelArray [] = $object;
                }
            }elseif($tempPatientId == $value->procedures_id){
                $insurance [] = ['insuranceID' => $value->insurance_id, 'insurance_company' => $value->insur_company_name, 'rates' => $value->rates];
                $object->insurance = $insurance;
                if((count($get_all)-1) == $key){
                    $excelArray [] = $object;
                }
            }else{
                $insurance = [];
                $excelArray [] = $object;

                $object = new \stdClass();
                $object->procedures_id = $value->procedures_id;
                $object->procedure_name = $value->procedure_name;
                $object->code = $value->code;
                $object->rate = $value->rate;
                $object->template = $value->template;
                $object->color_code = $value->color_code;
                $object->duration_h = $value->duration_h;
                $object->duration_m = $value->duration_m;
                $insurance [] = ['insuranceID' => $value->insurance_id, 'insurance_company' => $value->insur_company_name, 'rates' => $value->rates];
                $object->insurance = $insurance;

                $tempPatientId = $value->procedures_id;
                if((count($get_all)-1) == $key){
                    $excelArray [] = $object;
                }
            }
        }

        return response(['data' => $excelArray]);
    }

    public function import_procedures(Request $request){
        $extention = $request->file("importfile")->getClientOriginalExtension();
        if($extention == 'xlsx' || $extention == 'csv' || $extention == 'XLSX' || $extention == 'CSV'){
            $import_file = $request->file("importfile");
            Excel::import(new ImportProcedures, $import_file);
            return response(['success' => 'successfully imported!']);
        }else{
            return response(['message' => 'file should be xlsx or csv!']);
        }
    }
}
