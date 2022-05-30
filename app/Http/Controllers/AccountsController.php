<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceProcedure;
use App\Models\receipt;
use App\Models\InvoiceReceipt;
use App\Models\Lodgement;
use App\Models\LodgementReceipt;
use App\Models\Expenses;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
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

    public function update_invoice(Request $request){
        $id = $request->id;
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

        $Invoice = Invoice::where('id', $id)->first();

        if($Invoice){
            Invoice::where('id', $id)
            ->update(['bill_to' => $bill_to, 'date' => $date, 'income_category_id' => $income_category_id, 'insurance_company_id' => $insurance_company_id, 'insurance_number' => $insurance_number, 'patient_id' => $patient_id, 'solicitor_id' => $solicitor_id, 'sub_total' => $sub_total, 
                'tax' => $tax, 'tax_percentage' => $tax_percentage, 'net_total' => $net_total, 'memo' => $memo]);
        }else{
            return response(['message' => 'invoice not exist!']);
        }

        InvoiceProcedure::where('invoice_id', $id)->delete();

        foreach($get_procedure as $ids){
            $invoiceprocedure = new InvoiceProcedure;
            $invoiceprocedure->invoice_id = $invoice->id;
            $invoiceprocedure->procedures_id = $ids['id'];
            $invoiceprocedure->save();
        }

        return response(['success' => 'successfully update!']);
    }

    public function get_invoice(){
        $getInvoice = DB::table('invoice')
        ->join('invoice_proced_relat', 'invoice_proced_relat.invoice_id', 'invoice.id')
        ->join('procedures', 'invoice_proced_relat.procedures_id', 'procedures.id')
        ->join('patient', 'patient.user_id', 'invoice.patient_id')
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

    public function get_single_invoice($id){
        $get_bill_to = DB::table('invoice')->where('id', $id)->first()->bill_to;
        $getInvoice = DB::table('invoice')
        ->join('patient', 'patient.user_id', 'invoice.patient_id')
        ->join('income_category', 'income_category.id', 'invoice.income_category_id')
        ->leftjoin('contacts', 'contacts.id', 'invoice.solicitor_id')
        ->leftjoin('insurance_company', 'insurance_company.id', 'invoice.insurance_company_id')
        ->select('invoice.id', 'invoice.date', 'insurance_company.insur_company_name', 'invoice.insurance_number' ,'income_category.category_name' ,'patient.user_id', 'patient.dname', 'patient.surname', 'invoice.solicitor_id', 'contacts.fname', 'invoice.sub_total', 'invoice.tax', 'invoice.tax_percentage','invoice.net_total')
        ->where('invoice.id', $id)
        ->get();

        $get_procedure = DB::table('invoice_proced_relat')
        ->join('procedures', 'invoice_proced_relat.procedures_id', 'procedures.id')
        ->select('procedures.id', 'procedures.procedure_name','procedures.rate')
        ->where('invoice_proced_relat.invoice_id', $id)
        ->get();
        
        return response(['data' => $getInvoice, $get_procedure]);
    }

    public function create_receipt(Request $request){
        // date type should be date. formate 2021-12-14
        $date = $request->date;
        $received_from = $request->received_from;
        $mode_of_payment = $request->mode_of_payment;
        // if mode of payemnt os Cheque
        // date type should be date. formate 2021-12-14
        $cheque_date = $request->cheque_date;
        $cheque_no = $request->cheque_no;
        $bank_name = $request->bank_name;
        // if received_from type is patient
        $patient_id = $request->patient_id;
        // this is contact id
        // if received_from type is Third Party
        $third_party = $request->third_party;
        // if received_from type is Insurance Company
        $rec_insur_comp_id = $request->rec_insur_comp_id;

        $r_tax = $request->r_tax;
        $waived = $request->waived;
        $payment = $request->payment;

        $receipt_memo = $request->receipt_memo;
        
        $get_invoice = $request->invoice;

        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'received_from' => 'required',
            'mode_of_payment' => 'required',
            'r_tax' => 'required',
            'waived' => 'required',
            'payment' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $receipt = new receipt;
        $receipt->date = $date;
        $receipt->received_from = $received_from;
        $receipt->mode_of_payment = $mode_of_payment;
        $receipt->cheque_date = $cheque_date;
        $receipt->cheque_no = $cheque_no;
        $receipt->bank_name = $bank_name;
        $receipt->patient_id = $patient_id;
        $receipt->third_party = $third_party;
        $receipt->rec_insur_comp_id = $rec_insur_comp_id;
        $receipt->r_tax = $r_tax;
        $receipt->waived = $waived;
        $receipt->payment = $payment;
        $receipt->receipt_memo = $receipt_memo;
        $receipt->save();

        foreach($get_invoice as $ids){
            $invoice_receipt = new InvoiceReceipt;
            $invoice_receipt->receipt_id = $receipt->id;
            $invoice_receipt->invoice_id = $ids['id'];
            $invoice_receipt->relat_r_tax = $ids['relat_r_tax'];
            $invoice_receipt->relat_waived = $ids['relat_waived'];
            $invoice_receipt->relat_payment = $ids['relat_payment'];
            $invoice_receipt->save();
        }

        return response(['success' => 'successfully create!']);
    }

    public function update_receipt(Request $request){
        $id = $request->id;
        // date type should be date. formate 2021-12-14
        $date = $request->date;
        $received_from = $request->received_from;
        $mode_of_payment = $request->mode_of_payment;
        // if mode of payemnt os Cheque
        // date type should be date. formate 2021-12-14
        $cheque_date = $request->cheque_date;
        $cheque_no = $request->cheque_no;
        $bank_name = $request->bank_name;
        // if received_from type is patient
        $patient_id = $request->patient_id;
        // this is contact id
        // if received_from type is Third Party
        $third_party = $request->third_party;
        // if received_from type is Insurance Company
        $rec_insur_comp_id = $request->rec_insur_comp_id;

        $r_tax = $request->r_tax;
        $waived = $request->waived;
        $payment = $request->payment;

        $receipt_memo = $request->receipt_memo;
        
        $get_invoice = $request->invoice;

        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'received_from' => 'required',
            'mode_of_payment' => 'required',
            'r_tax' => 'required',
            'waived' => 'required',
            'payment' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $receipt = receipt::where('id', $id)->first();

        if($receipt){
            receipt::where('id', $id)
            ->update(['date' => $date, 'received_from' => $received_from, 'mode_of_payment' => $mode_of_payment, 'cheque_date' => $cheque_date, 'cheque_no' => $cheque_no, 'bank_name' => $bank_name, 'patient_id' => $patient_id, 'third_party' => $third_party, 
                'rec_insur_comp_id' => $rec_insur_comp_id, 'r_tax' => $r_tax, 'waived' => $waived, 'payment' => $payment, 'receipt_memo' => $receipt_memo]);
        }else{
            return response(['message' => 'receipt not exist!']);
        }

        InvoiceReceipt::where('receipt_id', $id)->delete();

        foreach($get_invoice as $ids){
            $invoice_receipt = new InvoiceReceipt;
            $invoice_receipt->receipt_id = $receipt->id;
            $invoice_receipt->invoice_id = $ids['id'];
            $invoice_receipt->relat_r_tax = $ids['relat_r_tax'];
            $invoice_receipt->relat_waived = $ids['relat_waived'];
            $invoice_receipt->relat_payment = $ids['relat_payment'];
            $invoice_receipt->save();
        }

        return response(['success' => 'successfully update!']);
    }

    public function get_receipt(){
        $excelArray = [];

        $get_receipt_patient = DB::table('receipt')
        ->join('patient', 'receipt.patient_id', 'patient.user_id')
        ->select('receipt.id', 'patient.surname', 'patient.dname', 'receipt.date', 'receipt.payment', 'receipt.cheque_date', 'receipt.cheque_no','receipt.bank_name')
        ->get();

        $get_receipt_contacts = DB::table('receipt')
        ->join('contacts', 'receipt.third_party', 'contacts.id')
        ->select('receipt.id', 'contacts.surname', 'contacts.dname', 'receipt.date', 'receipt.payment', 'receipt.cheque_date', 'receipt.cheque_no','receipt.bank_name')
        ->get();

        $get_receipt_insurance_company = DB::table('receipt')
        ->join('insurance_company', 'receipt.rec_insur_comp_id', 'insurance_company.id')
        ->select('receipt.id', 'insurance_company.insur_company_name', 'receipt.date', 'receipt.payment', 'receipt.cheque_date', 'receipt.cheque_no','receipt.bank_name')
        ->get();

        foreach($get_receipt_patient as $key => $patientval){
            $object = new \stdClass();
            $object->receipt_no = $patientval->id;
            $object->from = $patientval->surname. ' '. $patientval->dname;
            $object->date = $patientval->date;
            $object->amount = $patientval->payment;
            $object->bank_name = $patientval->bank_name;
            $object->cheque_date = $patientval->cheque_date;
            $object->cheque_no = $patientval->cheque_no;
            $excelArray [] = $object;
        }

        foreach($get_receipt_contacts as $key => $contactsval){
            $object = new \stdClass();
            $object->receipt_no = $contactsval->id;
            $object->from = $contactsval->surname. ' '. $contactsval->dname;
            $object->date = $contactsval->date;
            $object->amount = $contactsval->payment;
            $object->bank_name = $contactsval->bank_name;
            $object->cheque_date = $contactsval->cheque_date;
            $object->cheque_no = $contactsval->cheque_no;
            $excelArray [] = $object;
        }

        foreach($get_receipt_insurance_company as $key => $insuranceval){
            $object = new \stdClass();
            $object->receipt_no = $insuranceval->id;
            $object->from = $insuranceval->insur_company_name;
            $object->date = $insuranceval->date;
            $object->amount = $insuranceval->payment;
            $object->bank_name = $insuranceval->bank_name;
            $object->cheque_date = $insuranceval->cheque_date;
            $object->cheque_no = $insuranceval->cheque_no;
            $excelArray [] = $object;
        }

        return response(['data' => $excelArray]);
    }

    public function get_single_receipt($id){
        $excelArray = [];

        $get_received_from = DB::table('receipt')
        ->where('receipt.id', $id)
        ->first();

        if($get_received_from){
            $received_from_type = $get_received_from->received_from;
            if($received_from_type == 'patient'){
                $get_receipt_patient = DB::table('receipt')
                ->join('patient', 'receipt.patient_id', 'patient.user_id')
                ->select('receipt.id', 'receipt.date', 'receipt.received_from', 'receipt.mode_of_payment', 'receipt.cheque_date', 'receipt.cheque_no','receipt.bank_name', 'receipt.payment', 'patient.surname', 'patient.dname', 'patient.user_id', 'patient.address1', 'patient.address2', 'patient.address3', 'patient.address4', 'receipt.receipt_memo')
                ->where('receipt.id', $id)
                ->get();

                $get_relation = DB::table('invoice_receipt_relat')
                ->join('invoice', 'invoice_receipt_relat.invoice_id', 'invoice.id')
                ->join('receipt', 'invoice_receipt_relat.receipt_id', 'receipt.id')
                ->join('patient', 'receipt.patient_id', 'patient.user_id')
                ->select('invoice.id', 'patient.dname', 'patient.surname', 'invoice.date', 'invoice.sub_total', 'invoice.sub_total', 'invoice_receipt_relat.relat_r_tax' ,'invoice.net_total', 'invoice_receipt_relat.relat_waived', 'invoice_receipt_relat.relat_payment')
                ->where('receipt_id', $id)
                ->get();

                return response(['data' => $get_receipt_patient, $get_relation]);

            }elseif($received_from_type == 'third_party'){

                $get_receipt_contacts = DB::table('receipt')
                ->join('contacts', 'receipt.third_party', 'contacts.id')
                ->select('receipt.id', 'receipt.date', 'receipt.received_from', 'receipt.mode_of_payment', 'receipt.cheque_date', 'receipt.cheque_no','receipt.bank_name','receipt.payment', 'contacts.fname','contacts.surname', 'contacts.address1', 'contacts.address2', 'contacts.address3', 'contacts.address4', 'receipt.receipt_memo')
                ->where('receipt.id', $id)
                ->get();

                $get_relation = DB::table('invoice_receipt_relat')
                ->join('invoice', 'invoice_receipt_relat.invoice_id', 'invoice.id')
                ->join('receipt', 'invoice_receipt_relat.receipt_id', 'receipt.id')
                ->join('contacts', 'receipt.third_party', 'contacts.id')
                ->select('invoice.id', 'contacts.fname','contacts.surname', 'invoice.date', 'invoice.sub_total', 'invoice.sub_total', 'invoice_receipt_relat.relat_r_tax' ,'invoice.net_total', 'invoice_receipt_relat.relat_waived', 'invoice_receipt_relat.relat_payment')
                ->where('receipt_id', $id)
                ->get();

                return response(['data' => $get_receipt_patient, $get_relation]);

            }elseif($received_from_type == 'insurancecompany'){

                $get_receipt_insurance = DB::table('receipt')
                ->join('insurance_company', 'receipt.rec_insur_comp_id', 'insurance_company.id')
                ->select('receipt.id', 'receipt.date', 'receipt.received_from', 'receipt.mode_of_payment', 'receipt.cheque_date', 'receipt.cheque_no','receipt.bank_name','receipt.payment', 'insurance_company.insur_company_name', 'receipt.receipt_memo')
                ->where('receipt.id', $id)
                ->get();

                $get_relation = DB::table('invoice_receipt_relat')
                ->join('invoice', 'invoice_receipt_relat.invoice_id', 'invoice.id')
                ->join('receipt', 'invoice_receipt_relat.receipt_id', 'receipt.id')
                ->join('insurance_company', 'receipt.rec_insur_comp_id', 'insurance_company.id')
                ->select('invoice.id', 'insurance_company.insur_company_name', 'invoice.date', 'invoice.sub_total', 'invoice.sub_total', 'invoice_receipt_relat.relat_r_tax' ,'invoice.net_total', 'invoice_receipt_relat.relat_waived', 'invoice_receipt_relat.relat_payment')
                ->where('receipt_id', $id)
                ->get();

                return response(['data' => $get_receipt_insurance, $get_relation]);
                
            }
        }


        $get_receipt_patient = DB::table('receipt')
        ->join('patient', 'receipt.patient_id', 'patient.user_id')
        ->select('receipt.id', 'patient.surname', 'patient.dname', 'receipt.date', 'receipt.payment')
        ->where('receipt.id', $id)
        ->first();

        $get_receipt_contacts = DB::table('receipt')
        ->join('contacts', 'receipt.third_party', 'contacts.id')
        ->select('receipt.id', 'contacts.surname', 'contacts.dname', 'receipt.date', 'receipt.payment')
        ->where('receipt.id', $id)
        ->first();

        $get_receipt_insurance_company = DB::table('receipt')
        ->join('insurance_company', 'receipt.rec_insur_comp_id', 'insurance_company.id')
        ->select('receipt.id', 'insurance_company.insur_company_name', 'receipt.date', 'receipt.payment')
        ->where('receipt.id', $id)
        ->first();

        $object = new \stdClass();
        $object->receipt_no = $get_receipt_patient->id;
        $object->from = $get_receipt_patient->surname. ' '. $get_receipt_patient->dname;
        $object->date = $get_receipt_patient->date;
        $object->amount = $get_receipt_patient->payment;
        $excelArray [] = $object;

        $object = new \stdClass();
        $object->receipt_no = $get_receipt_contacts->id;
        $object->from = $get_receipt_contacts->surname. ' '. $get_receipt_contacts->dname;
        $object->date = $get_receipt_contacts->date;
        $object->amount = $get_receipt_contacts->payment;
        $excelArray [] = $object;

        $object = new \stdClass();
        $object->receipt_no = $get_receipt_insurance_company->id;
        $object->from = $get_receipt_insurance_company->insur_company_name;
        $object->date = $get_receipt_insurance_company->date;
        $object->amount = $get_receipt_insurance_company->payment;
        $excelArray [] = $object;

        return response(['data' => $excelArray]);
    }

    public function get_received_from($type, $id){
        if(trim($type) == 'patient'){
            $getInvoice = DB::table('invoice')
            ->join('patient', 'patient.user_id', 'invoice.patient_id')
            ->where('patient_id', $id)
            ->select('invoice.id', 'patient.dname', 'patient.surname', 'invoice.date', 'invoice.sub_total', 'invoice.tax', 'invoice.tax_percentage', 'invoice.net_total')
            ->get();
        }elseif(trim($type) == 'third_party'){
            $getInvoice = DB::table('invoice')
            ->join('contacts', 'contacts.id', 'invoice.solicitor_id')
            ->where('invoice.solicitor_id', $id)
            ->select('invoice.id', 'contacts.fname', 'contacts.surname', 'invoice.date', 'invoice.sub_total', 'invoice.tax', 'invoice.tax_percentage', 'invoice.net_total')
            ->get();
        }elseif(trim($type) == 'insurancecompany'){
            $getInvoice = DB::table('invoice')
            ->join('insurance_company', 'insurance_company.id', 'invoice.insurance_company_id')
            ->where('invoice.insurance_company_id', $id)
            ->select('invoice.id', 'insurance_company.insur_company_name', 'invoice.date', 'invoice.sub_total', 'invoice.tax', 'invoice.tax_percentage', 'invoice.net_total')
            ->get();
        }

        return response(['data' => $getInvoice]);
    }

    public function create_lodgement(Request $request){
        // date type should be date. formate 2021-12-14
        $date = $request->date;
        $bank_id = $request->bank_id;
        $total_amount = $request->total_amount;
        $lodgement_memo = $request->lodgement_memo;

        $get_receipt_id = $request->receipt_id;

        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'bank_id' => 'required|exists:bank_details,id',
            'total_amount' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $lodgement = new Lodgement;
        $lodgement->date = $date;
        $lodgement->bank_id = $bank_id;
        $lodgement->total_amount = $total_amount;
        $lodgement->lodgement_memo = $lodgement_memo;
        $lodgement->save();

        foreach($get_receipt_id as $ids){
            $lodgementreceipt = new LodgementReceipt;
            $lodgementreceipt->lodgement_id = $lodgement->id;
            $lodgementreceipt->receipt_id = $ids['id'];
            $lodgementreceipt->save();
        }

        return response(['success' => 'successfully create!']);
    }

    public function update_lodgement(Request $request){
        $id = $request->id;
        // date type should be date. formate 2021-12-14
        $date = $request->date;
        $bank_id = $request->bank_id;
        $total_amount = $request->total_amount;
        $lodgement_memo = $request->lodgement_memo;

        $get_receipt_id = $request->receipt_id;

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'date' => 'required',
            'bank_id' => 'required|exists:bank_details,id',
            'total_amount' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $lodgement = Lodgement::where('id', $id)->first();

        if($lodgement){
            Lodgement::where('id', $id)
            ->update(['date' => $date, 'bank_id' => $bank_id, 'total_amount' => $total_amount, 'lodgement_memo' => $lodgement_memo]);
        }else{
            return response(['message' => 'lodgement not exist!']);
        }

        LodgementReceipt::where('lodgement_id', $id)->delete();

        foreach($get_receipt_id as $ids){
            $lodgementreceipt = new LodgementReceipt;
            $lodgementreceipt->lodgement_id = $lodgement->id;
            $lodgementreceipt->receipt_id = $ids['id'];
            $lodgementreceipt->save();
        }

        return response(['success' => 'successfully updated!']);
    }

    public function get_lodgement(){
        $get_lodgement_all = DB::table('lodgement')
        ->join('bank_details', 'bank_details.id', 'lodgement.bank_id')
        ->select('lodgement.id', 'lodgement.date', 'lodgement.bank_id' ,'bank_details.bank_name', 'lodgement.total_amount', 'lodgement.lodgement_memo')
        ->get();

        $get_relation = DB::table('lodgement_receipt_rel')
        ->join('receipt', 'receipt.id', 'lodgement_receipt_rel.receipt_id')
        ->select('lodgement_receipt_rel.receipt_id', 'receipt.date', 'receipt.mode_of_payment', 'receipt.payment')
        ->get();

        $get_lodgement_all [] = ['receipt_id' => $get_relation];

        return response(['data' => $get_lodgement_all]);
    }

    public function get_single__lodgement($id){
        $get_lodgement = DB::table('lodgement')
        ->join('bank_details', 'bank_details.id', 'lodgement.bank_id')
        ->select('lodgement.id', 'lodgement.date', 'lodgement.bank_id' ,'bank_details.bank_name', 'lodgement.total_amount', 'lodgement.lodgement_memo')
        ->where('lodgement.id', $id)
        ->get();

        $get_relation = DB::table('lodgement_receipt_rel')
        ->join('receipt', 'receipt.id', 'lodgement_receipt_rel.receipt_id')
        ->select('lodgement_receipt_rel.receipt_id', 'receipt.date', 'receipt.mode_of_payment', 'receipt.payment')
        ->where('lodgement_receipt_rel.lodgement_id', $id)
        ->get();

        $get_lodgement [] = ['receipt_id' => $get_relation];

        return response(['data' => $get_lodgement]);
    }

    public function create_expenses(Request $request){
        // date type should be date. formate 2021-12-14
        $expenses_date = $request->expenses_date;
        $expenses_amount = $request->expenses_amount;
        $expenses_category = $request->expenses_category;
        $expenses_payment_mode = $request->expenses_payment_mode;
        // if payment mode is cheque
        $expens_bank_name = $request->expens_bank_name;
        $expens_cheque_no = $request->expens_cheque_no;
        $expens_cheque_date = $request->expens_cheque_date;
        // if payment mode is credit card
        $expens_card_type = $request->expens_card_type;
        $expens_card_name = $request->expens_card_name;
        $expens_card_no = $request->expens_card_no;
        $expens_card_expi_date = $request->expens_card_expi_date;
        // if payment mode is direct debit
        $expens_refrence_no = $request->expens_refrence_no;
        $expens_ref_bank_name = $request->expens_ref_bank_name;

        $expens_details = $request->expens_details;

        // $file1 = $request->file('expens_file_one');
        // $name1 = time() . Str::random(40) . '.' . $file1->getClientOriginalExtension();
        // Storage::disk('public')->put('/uploadimage/'. $name1, $file1);
        // // Storage::disk('public')->url($file1);

        // $file2 = $request->file('expens_file_two');
        // $name2 = time() . Str::random(40) . '.' . $file2->getClientOriginalExtension();
        // Storage::disk('public')->put('/uploadimage/'. $name2, $file2);
        // // Storage::disk('public')->url($file2);

        // $file3 = $request->file('expens_file_three');
        // $name3 = time() . Str::random(40) . '.' . $file2->getClientOriginalExtension();
        // Storage::disk('public')->put('/uploadimage/'. $name3, $file3);
        // $url = Storage::disk('public')->url($name3);


        $extention1 = $request->file("expens_file_one")->getClientOriginalExtension();
        $fileName1 = rand(11111111, 99999999).'.'.$extention1;
        $request->file("expens_file_one")->move(public_path("files/"), $fileName1);

        $extention2 = $request->file("expens_file_two")->getClientOriginalExtension();
        $fileName2 = rand(11111111, 99999999).'.'.$extention2;
        $request->file("expens_file_two")->move(public_path("files/"), $fileName2);

        $extention3 = $request->file("expens_file_three")->getClientOriginalExtension();
        $fileName3 = rand(11111111, 99999999).'.'.$extention3;
        $request->file("expens_file_three")->move(public_path("files/"), $fileName3);
        
        // $fileURL = url('files/'.$fileName1);

        // $file1 = $request->file('expens_file_one');
        // $path1 = public_path() . '/uploads/file';
        // $file1->move($path1, $file1->getClientOriginalName());

        // $file2 = $request->file('expens_file_two');
        // $path2 = public_path() . '/uploads/file';
        // $file2->move($path2, $file2->getClientOriginalName());

        // $file3 = $request->file('expens_file_three');
        // $path3 = public_path() . '/uploads/file';
        // $file3->move($path3, $file3->getClientOriginalName());

        $validator = Validator::make($request->all(), [
            'expenses_date' => 'required',
            'expenses_amount' => 'required',
            'expenses_category' => 'required',
            'expenses_payment_mode' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $expenses = new Expenses;
        $expenses->expenses_date = $expenses_date;
        $expenses->expenses_amount = $expenses_amount;
        $expenses->expenses_category = $expenses_category;
        $expenses->expenses_payment_mode = $expenses_payment_mode;
        $expenses->expens_bank_name = $expens_bank_name;
        $expenses->expens_cheque_no = $expens_cheque_no;
        $expenses->expens_cheque_date = $expens_cheque_date;
        $expenses->expens_card_type = $expens_card_type;
        $expenses->expens_card_name = $expens_card_name;
        $expenses->expens_card_no = $expens_card_no;
        $expenses->expens_card_expi_date = $expens_card_expi_date;
        $expenses->expens_refrence_no = $expens_refrence_no;
        $expenses->expens_ref_bank_name = $expens_ref_bank_name;
        $expenses->expens_details = $expens_details;
        $expenses->expens_file_one = $fileName1;
        $expenses->expens_file_two = $fileName2;
        $expenses->expens_file_three = $fileName3;
        $expenses->save();

        return response(['success' => 'successfully create!']);
    }

    public function update_expenses(Request $request){
        $id = $request->id;
        // date type should be date. formate 2021-12-14
        $expenses_date = $request->expenses_date;
        $expenses_amount = $request->expenses_amount;
        $expenses_category = $request->expenses_category;
        $expenses_payment_mode = $request->expenses_payment_mode;
        // if payment mode is cheque
        $expens_bank_name = $request->expens_bank_name;
        $expens_cheque_no = $request->expens_cheque_no;
        $expens_cheque_date = $request->expens_cheque_date;
        // if payment mode is credit card
        $expens_card_type = $request->expens_card_type;
        $expens_card_name = $request->expens_card_name;
        $expens_card_no = $request->expens_card_no;
        $expens_card_expi_date = $request->expens_card_expi_date;
        // if payment mode is direct debit
        $expens_refrence_no = $request->expens_refrence_no;
        $expens_ref_bank_name = $request->expens_ref_bank_name;

        $expens_details = $request->expens_details;

        $extention1 = $request->file("expens_file_one")->getClientOriginalExtension();
        $fileName1 = rand(11111111, 99999999).'.'.$extention1;
        $request->file("expens_file_one")->move(public_path("files/"), $fileName1);

        $extention2 = $request->file("expens_file_two")->getClientOriginalExtension();
        $fileName2 = rand(11111111, 99999999).'.'.$extention2;
        $request->file("expens_file_two")->move(public_path("files/"), $fileName2);

        $extention3 = $request->file("expens_file_three")->getClientOriginalExtension();
        $fileName3 = rand(11111111, 99999999).'.'.$extention3;
        $request->file("expens_file_three")->move(public_path("files/"), $fileName3);

        $validator = Validator::make($request->all(), [
            'expenses_date' => 'required',
            'expenses_amount' => 'required',
            'expenses_category' => 'required',
            'expenses_payment_mode' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $Expenses = Expenses::where('id', $id)->first();

        if($Expenses){
            Expenses::where('id', $id)
            ->update(['expenses_date' => $expenses_date, 'expenses_amount' => $expenses_amount, 'expenses_category' => $expenses_category, 'expenses_payment_mode' => $expenses_payment_mode, 'expens_bank_name' => $expens_bank_name, 'expens_cheque_no' => $expens_cheque_no, 'expens_cheque_date' => $expens_cheque_date, 'expens_card_type' => $expens_card_type, 'expens_card_name' => $expens_card_name, 'expens_card_no' => $expens_card_no, 'expens_card_expi_date' => $expens_card_expi_date, 'expens_refrence_no' => $expens_refrence_no, 'expens_ref_bank_name' => $expens_ref_bank_name, 'expens_details' => $expens_details, 'expens_file_one' => $fileName1, 'expens_file_two' => $fileName2, 'expens_file_three' => $fileName3]);

            return response(['success' => 'successfully update!']);
        }else{
            return response(['message' => 'expenses not exist!']);
        }
    }

    public function get_expenses(){
        $get_all = DB::table('expenses')
        ->select('expenses.id', 'expenses.expenses_category', 'expenses.expenses_date','expenses.expenses_payment_mode', 'expenses.expenses_amount')
        ->get();

        return response(['data' => $get_all]);
    }

    public function get_single_expenses($id){
        $app_name = 'https://demoimed.nextbitsolution.com/files/';
        $get_expenses = DB::table('expenses')
        ->where('expenses.id', $id)
        ->first();

        $object = new \stdClass();
        $object->expenses_id = $get_expenses->id;
        $object->expenses_date = $get_expenses->expenses_date;
        $object->expenses_amount = $get_expenses->expenses_amount;
        $object->expenses_category = $get_expenses->expenses_category;
        $object->expenses_payment_mode = $get_expenses->expenses_payment_mode;
        $object->expens_bank_name = $get_expenses->expens_bank_name;
        $object->expens_cheque_no = $get_expenses->expens_cheque_no;
        $object->expens_cheque_date = $get_expenses->expens_cheque_date;
        $object->expens_card_type = $get_expenses->expens_card_type;
        $object->expens_card_name = $get_expenses->expens_card_name;
        $object->expens_card_no = $get_expenses->expens_card_no;
        $object->expens_card_expi_date = $get_expenses->expens_card_expi_date;
        $object->expens_refrence_no = $get_expenses->expens_refrence_no;
        $object->expens_ref_bank_name = $get_expenses->expens_ref_bank_name;
        $object->expens_details = $get_expenses->expens_details;

        $object->expens_file_one = $app_name . $get_expenses->expens_file_one;
        $object->expens_file_two = $app_name . $get_expenses->expens_file_two;
        $object->expens_file_three = $app_name . $get_expenses->expens_file_three;

        return response(['data' => $object]);
    }
}
