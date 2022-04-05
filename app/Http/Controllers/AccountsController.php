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
        $sub_total = $request->sub_total;
        $tax = $request->tax;
        $tax_percentage = $request->tax_percentage;
        $net_total = $request->net_total;
        $memo = $request->memo;

        // $get_procedure = explode(',', $procedures);

        $validator = Validator::make($request->all(), [
            'bill_to' => 'required',
            'date' => 'required',
            'income_category_id' => 'required|exists:income_category,id',
            'patient_id' => 'required|exists:patient,user_id'
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
        $invoice->sub_total = $sub_total;
        $invoice->tax = $tax;
        $invoice->tax_percentage = $tax_percentage;
        $invoice->net_total = $net_total;
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
        ->select('invoice.id', 'invoice.date', 'patient.user_id', 'patient.dname', 'patient.surname', 'income_category.category_name', 'invoice.sub_total', 'invoice.tax_percentage','invoice.paid' ,'invoice.net_total')
        ->get();

        // dd($getInvoice);

        $tempPatientId = null;
        $excelArray = [];

        foreach($getInvoice as $key => $value){
            if($tempPatientId == null){
                $object = new \stdClass();
                $object->invoice_no = $value->id;
                $object->date = $value->date;
                $object->patient_id = $value->user_id;
                $object->patient = $value->dname. ' '. $value->surname;
                $object->income_location = $value->category_name;
                $object->amount = $value->sub_total;
                $object->ml_tax = $value->tax_percentage;
                $object->paid = $value->paid;
                $object->balance = $value->net_total;

                $tempPatientId = $value->id;
                if((count($getInvoice)-1) == $key){
                    $excelArray [] = $object;
                }
            }elseif($tempPatientId != $value->id){
                $excelArray [] = $object;
                
                $object = new \stdClass();
                $object->invoice_no = $value->id;
                $object->date = $value->date;
                $object->patient_id = $value->user_id;
                $object->patient = $value->dname. ' '. $value->surname;
                $object->income_location = $value->category_name;
                $object->amount = $value->sub_total;
                $object->ml_tax = $value->tax_percentage;
                $object->paid = $value->paid;
                $object->balance = $value->net_total;

                $tempPatientId = $value->id;
                if((count($getInvoice)-1) == $key){
                    $excelArray [] = $object;
                }
            }else{
                if((count($getInvoice)-1) == $key){
                    $excelArray [] = $object;
                }
            }
        }
        return response(['data' => $excelArray]);

    }
}
