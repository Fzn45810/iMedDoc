<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceProcedure;
use App\Models\receipt;
use App\Models\InvoiceReceipt;
use App\Models\Lodgement;
use App\Models\LodgementReceipt;
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
        $get_all = DB::table('lodgement')
        ->join('bank_details', 'bank_details.id', 'lodgement.bank_id')
        ->select('lodgement.id', 'lodgement.date', 'bank_details.bank_name', 'lodgement.total_amount')
        ->get();
        return response(['data' => $get_all]);
    }

    public function get_single__lodgement($id){
        $get_lodgement = DB::table('lodgement')
        ->join('bank_details', 'bank_details.id', 'lodgement.bank_id')
        ->select('lodgement.id', 'lodgement.date', 'bank_details.bank_name', 'lodgement.total_amount')
        ->where('lodgement.id', $id)
        ->get();

        $get_relation = DB::table('lodgement_receipt_rel')
        ->join('receipt', 'receipt.id', 'lodgement_receipt_rel.receipt_id')
        ->select('receipt.id', 'receipt.date', 'receipt.mode_of_payment', 'receipt.payment')
        ->where('lodgement_receipt_rel.lodgement_id', $id)
        ->get();

        return response(['data' => $get_lodgement, $get_relation]);
    }
}
