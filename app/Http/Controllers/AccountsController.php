<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceProcedure;
use Illuminate\Support\Facades\Validator;
use DB;

class AccountsController extends Controller
{
    public function create_invoice(Request $request){
        $bill_to = $request->bill_to;
        // date type should be date. formate 2021-12-14
        $date = $request->date;
        $income_category_id = $request->income_category_id;
        $insurance_company_id = $request->insurance_company_id;
        $insurance_number = $request->insurance_number;
        $patient_id = $request->patient_id;
        // this is contact id
        $solicitor_id = $request->solicitor_id;
        $get_procedure = $request->procedures;
        $memo = $request->memo;

        // $get_procedure = explode(',', $procedures);

        $validator = Validator::make($request->all(), [
            'bill_to' => 'required',
            'date' => 'required',
            'income_category_id' => 'required|exists:income_category,id',
            'insurance_number' => 'required',
            'patient_id' => 'required|exists:patient,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $invoice = new Invoice;
        $invoice->bill_to = $bill_to;
        $invoice->date = $date;
        $invoice->income_category_id = $income_category_id;
        $invoice->insurance_company_id = $insurance_company_id;
        $invoice->insurance_number = $insurance_number;
        $invoice->patient_id = $patient_id;
        $invoice->solicitor_id = $solicitor_id;
        $invoice->memo = $memo;
        $invoice->save();

        foreach($get_procedure as $ids){
            $invoiceprocedure = new InvoiceProcedure;
            $invoiceprocedure->invoice_id = $invoice->id;
            $invoiceprocedure->procedures_id = $ids['id'];
            $invoiceprocedure->save();
        }

        return response(['success' => 'successfully create!']);
    }

    public function get_invoice(){
        $getInvoice = DB::table('invoice')
        ->join('invoice_proced_relat', 'invoice_proced_relat.invoice_id', 'invoice.id')
        ->join('procedures', 'invoice_proced_relat.procedures_id', 'procedures.id')
        ->join('patient', 'patient.id', 'invoice.patient_id')
        ->join('income_category', 'income_category.id', 'invoice.income_category_id')
        ->get();

        dd($getInvoice);

        $tempPatientId = null;

        foreach($getInvoice as $key => $value){
            if($tempPatientId == null){
                $object = new \stdClass();
                $object->insurance_number = $value->insurance_number;
                $object->date = $value->date;
                $object->patient = $value->user_id;
                $tempPatientId = $value->user_id;
                if((count($getInvoice)-1) == $key){
                    $excelArray [] = $object;
                }
            }elseif($tempPatientId == $value->employe_id){
                $payrate [] = $value->payrate;
                $object->starts = $shifts;
                $object->payrate = $payrate;
                if((count($getInvoice)-1) == $key){
                    $excelArray [] = $object;
                }
            }else{
                $shifts = [];
                $payrate = [];
                $excelArray [] = $object;

                $shifts [] = date('h:i a', strtotime($value->startshift)).' to '.date('h:i a', strtotime($value->endshift));
                $payrate [] = $value->payrate;
                $object = new \stdClass();
                $object->fName = $value->fName;
                $object->lName = $value->lName;
                $object->Contactnumber = $value->Contactnumber;
                $object->c_countrycode = $value->c_countrycode;
                $object->starts = $shifts;
                $object->payrate = $payrate;
                $object->employe_id = $value->employe_id;
                $object->name = $value->name;
                $object->is_checkIn = $value->is_checkIn;
                $tempPatientId = $value->employe_id;
                if((count($getInvoice)-1) == $key){
                    $excelArray [] = $object;
                }
            }
        }
        return response(['data' => $getInvoice]);

    }
}
