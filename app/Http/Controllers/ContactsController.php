<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contacts;
use Illuminate\Support\Facades\Validator;
use App\Imports\ImportContacts;
use DB;
use Excel;

class ContactsController extends Controller
{
    public function create(Request $request){
        $contact_type_id = $request->contact_type_id;
        $title_type_id = $request->title_type_id;
        $surname = $request->surname;
        $fname = $request->fname;
        $dname = $request->dname;
        $entityname = $request->entityname;
        $address1 = $request->address1;
        $address2 = $request->address2;
        $address3 = $request->address3;
        $address4 = $request->address4;
        $workphone = $request->workphone;
        $homephone = $request->homephone;
        $mobile = $request->mobile;
        $email = $request->email;
        $website = $request->website;
        $fax = $request->fax;

        $validator = Validator::make($request->all(), [
            'contact_type_id' => 'required|exists:contact_type,id',
            'surname' => 'required',
            'dname' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        if(!is_null($title_type_id)){
            $validator = Validator::make($request->all(), [
                'title_type_id' => 'required|exists:title_table,id'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }
        }

        if(!is_null($email)){
            $validator = Validator::make($request->all(), [
                'email' => 'required|email'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }
        }

        $contacts = new Contacts;
        $contacts->contact_type_id = $contact_type_id;
        $contacts->title_type_id = $title_type_id;
        $contacts->surname = $surname;
        $contacts->fname = $fname;
        $contacts->dname = $dname;
        $contacts->entityname = $entityname;
        $contacts->address1 = $address1;
        $contacts->address2 = $address2;
        $contacts->address3 = $address3;
        $contacts->address4 = $address4;
        $contacts->workphone = $workphone;
        $contacts->homephone = $homephone;
        $contacts->mobile = $mobile;
        $contacts->email = $email;
        $contacts->website = $website;
        $contacts->fax = $fax;
        $contacts->save();

        return response(['success' => 'successfully create!']);
    }

    public function update(Request $request){
        $id = $request->id;
        $contact_type_id = $request->contact_type_id;
        $title_type_id = $request->title_type_id;
        $surname = $request->surname;
        $fname = $request->fname;
        $dname = $request->dname;
        $entityname = $request->entityname;
        $address1 = $request->address1;
        $address2 = $request->address2;
        $address3 = $request->address3;
        $address4 = $request->address4;
        $workphone = $request->workphone;
        $homephone = $request->homephone;
        $mobile = $request->mobile;
        $email = $request->email;
        $website = $request->website;
        $fax = $request->fax;

        $validator = Validator::make($request->all(), [
            'contact_type_id' => 'required|exists:contact_type,id',
            'surname' => 'required',
            'dname' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        if(!is_null($title_type_id)){
            $validator = Validator::make($request->all(), [
                'title_type_id' => 'required|exists:title_table,id'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }
        }

        if(!is_null($email)){
            $validator = Validator::make($request->all(), [
                'email' => 'required|email'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }
        }

        Contacts::where('id', $id)->update(['contact_type_id' => $contact_type_id, 'title_type_id' => $title_type_id, 'surname' => $surname, 'fname' => $fname, 'dname' => $dname, 'entityname' => $entityname, 'address1' => $address1, 'address2' => $address2, 'address3' => $address3, 'address4' => $address4, 'workphone' => $workphone, 'homephone' => $homephone, 'mobile' => $mobile, 'email' => $email, 'website' => $website, 'fax' => $fax]);

        return response(['success' => 'successfully updated!']);
    }

    public function single_contact($id){
        $getall = DB::table('contacts')
        ->join('contact_type', 'contact_type.id', '=', 'contacts.contact_type_id')
        ->join('title_table', 'title_table.id', '=', 'contacts.title_type_id')
        ->where('contacts.id', $id)
        ->get();

        return response(['data' => $getall]);
    }

    public function get(){
        $getall = DB::table('contacts')
        ->join('contact_type', 'contact_type.id', '=', 'contacts.contact_type_id')
        ->leftjoin('title_table', 'title_table.id', '=', 'contacts.title_type_id')
        ->select('contacts.id', 'title_name', 'dname', 'email', 'workphone', 'mobile', 'address1', 'address2')
        ->get();

        return response(['data' => $getall]);
    }

    public function contact_type($type){
        $getall = DB::table('contacts')
        ->join('contact_type', 'contact_type.id', '=', 'contacts.contact_type_id')
        ->leftjoin('title_table', 'title_table.id', '=', 'contacts.title_type_id')
        ->where('type_name', $type)
        ->get();

        return response(['data' => $getall]);
    }

    public function import_contact(Request $request){
        $extention = $request->file("importfile")->getClientOriginalExtension();
        if($extention == 'xlsx' || $extention == 'csv' || $extention == 'XLSX' || $extention == 'CSV'){

            $import_file = $request->file("importfile");
            Excel::import(new ImportContacts, $import_file);
            return response(['success' => 'successfully imported!']);
            
        }else{
            return response(['message' => 'file should be xlsx or csv!']);
        }
    }
}
