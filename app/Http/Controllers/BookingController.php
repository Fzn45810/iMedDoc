<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PatientModel;
use App\Models\User;
use App\Models\PatientType;
use App\Models\ContactType;
use App\Models\title;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Imports\ImportPatient;
use App\Imports\ImportTitleType;
use App\Imports\ImportContactType;
use Excel;
use Illuminate\Support\Facades\Hash;

class BookingController extends Controller
{
    /**
    * Register api
    *
    * @return \Illuminate\Http\Response
    */
    public function add_patient(Request $request){
        $title_type_id = $request->title_type_id;
        $surname  = $request->surname;
        $fname = $request->fname;
        $dname = $request->dname;
        $address1 = $request->address1;
        $address2 = $request->address2;
        $address3 = $request->address3;
        $address4 = $request->address4;
        $gp = $request->gp;
        $solicitor  = $request->solicitor;
        $referingDr = $request->referingDr;
        $pharmacy = $request->pharmacy;
        $dateOfAccident = $request->dateOfAccident;
        $timeOfAccident = $request->timeOfAccident;
        $primaryDiagnosisType = $request->primaryDiagnosisType;
        $side = $request->side;
        $dateOfBirth = $request->dateOfBirth;
        $age = $request->age;
        $gender = $request->gender;
        $homePhone = $request->homePhone;
        $mobile  = $request->mobile;
        $occupation  = $request->occupation;
        $email = $request->email;
        $maritalStatus = $request->maritalStatus;
        $religion = $request->religion;
        $patientType = $request->patientType;
        $caseRefNo  = $request->caseRefNo;
        $notes = $request->notes;
        $primaryDiagnosis = $request->primaryDiagnosis;

        $password = mt_rand(100000,999999);

        $validator = Validator::make($request->all(), [
            'title_type_id' => 'required|exists:title_table,id',
            'email' => 'required|email',
            'surname' => 'required',
            'fname' => 'required',
            'gp' => 'exists:contacts,id',
            'solicitor' => 'exists:contacts,id',
            'referingDr' => 'exists:contacts,id',
            'pharmacy' => 'exists:contacts,id',
            'patientType' => 'exists:patient_type,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $user = User::where('email', $email)->first();

        if(!$user){
            $input = $request->all();
            $input['email'] = $email;
            $input['password'] = bcrypt($password);
            $input['fname'] = $fname;
            $user = User::create($input);

            $mypatient = new PatientModel;
            $mypatient->user_id = $user->id;
            $mypatient->title_type_id = $title_type_id;
            $mypatient->surname = $surname;
            $mypatient->dname = $dname;
            $mypatient->address1 = $address1;
            $mypatient->address2 = $address2;
            $mypatient->address3 = $address3;
            $mypatient->address4 = $address4;
            $mypatient->gp = $gp;
            $mypatient->solicitor = $solicitor;
            $mypatient->referingDr = $referingDr;
            $mypatient->pharmacy = $pharmacy;
            $mypatient->dateOfAccident = $dateOfAccident;
            $mypatient->timeOfAccident = $timeOfAccident;
            $mypatient->primaryDiagnosisType = $primaryDiagnosisType;
            $mypatient->side = $side;
            $mypatient->dateOfBirth = $dateOfBirth;
            $mypatient->age = $age;
            $mypatient->gender = $gender;
            $mypatient->homePhone = $homePhone;
            $mypatient->mobile = $mobile;
            $mypatient->occupation = $occupation;
            $mypatient->maritalStatus = $maritalStatus;
            $mypatient->religion = $religion;
            $mypatient->patientType = $patientType;
            $mypatient->caseRefNo = $caseRefNo;
            $mypatient->notes = $notes;
            $mypatient->primaryDiagnosis = $primaryDiagnosis;
            $mypatient->save();

            return response(['success' => 'successfully register!']);

        }else{
            return response(['message' => 'patient already register!']);
        }
    }

    public function update_patient(Request $request){
        $user_id = $request->user_id;
        $title_type_id = $request->title_type_id;
        $surname  = $request->surname;
        $fname = $request->fname;
        $dname = $request->dname;
        $address1 = $request->address1;
        $address2 = $request->address2;
        $address3 = $request->address3;
        $address4 = $request->address4;
        $gp = $request->gp;
        $solicitor  = $request->solicitor;
        $referingDr = $request->referingDr;
        $pharmacy = $request->pharmacy;
        $dateOfAccident = $request->dateOfAccident;
        $referralDate = $request->referralDate;
        $timeOfAccident = $request->timeOfAccident;
        $primaryDiagnosisType = $request->primaryDiagnosisType;
        $side = $request->side;
        $dateOfBirth = $request->dateOfBirth;
        $age  = $request->age;
        $gender = $request->gender;
        $homePhone = $request->homePhone;
        $mobile  = $request->mobile;
        $occupation  = $request->occupation;
        $email = $request->email;
        $maritalStatus = $request->maritalStatus;
        $religion = $request->religion;
        $patientType = $request->patientType;
        $caseRefNo  = $request->caseRefNo;
        $notes = $request->notes;
        $primaryDiagnosis = $request->primaryDiagnosis;

        $insurance_comp_id = $request->insurance_comp_id;
        $insurance_plane_id = $request->insurance_plan_id;
        $insurance_number = $request->insurance_number;

        $validator = Validator::make($request->all(), [
            'title_type_id' => 'required|exists:title_table,id',
            'email' => 'required|email',
            'surname' => 'required',
            'fname' => 'required',
            'gp' => 'required|exists:contacts,id',
            'solicitor' => 'required|exists:contacts,id',
            'referingDr' => 'required|exists:contacts,id',
            'pharmacy' => 'required|exists:contacts,id',
            'patientType' => 'required|exists:patient_type,id',
            'insurance_comp_id' => 'required|exists:insurance_company,id',
            'insurance_plan_id' => 'required|exists:insurance_plane,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $user = User::where('id', $user_id)->first();

        if($user){
            User::where('id', $user_id)
            ->update(['email' => $email, 'fname' => $fname]);

            PatientModel::where('user_id', $user_id)
            ->update(['title_type_id' => $title_type_id, 'surname' => $surname, 'dname' => $dname,
                'address1' => $address1, 'address2' => $address2, 'address3' => $address3, 'address4' => $address4, 'gp' => $gp, 'solicitor' => $solicitor, 'referingDr' => $referingDr, 'pharmacy' => $pharmacy, 'dateOfAccident' => $dateOfAccident,  'referralDate' => $referralDate,'timeOfAccident' => $timeOfAccident, 'primaryDiagnosisType' => $primaryDiagnosisType, 'side' => $side, 'dateOfBirth' => $dateOfBirth, 'age' => $age, 'gender' => $gender ,'homePhone' => $homePhone, 'mobile' => $mobile, 'occupation' => $occupation, 'maritalStatus' => $maritalStatus, 'religion' => $religion, 'patientType' => $patientType, 'caseRefNo' => $caseRefNo, 'notes' => $notes, 'primaryDiagnosis' => $primaryDiagnosis, 'insurance_comp_id' => $insurance_comp_id, 'insurance_plane_id' => $insurance_plane_id, 'insurance_number' => $insurance_number
            ]);


            return response(['success' => 'successfully updated!']);

        }else{
            return response(['message' => 'patient not exist!']);
        }
    }

    public function get_all_patient(){
        $getPatient = DB::table('users')
        ->join('patient', 'patient.user_id', 'users.id')
        ->leftjoin('title_table', 'title_table.id', '=', 'patient.title_type_id')
        ->select('users.id', 'title_table.title_name', 'dname', 'dateOfBirth', 'email', 'homePhone', 'mobile', 'address1', 'address2')->get();
        return response(['data' => $getPatient]);
    }

    public function get_single_patient($id){
        $get_patient = DB::table('users')
        ->join('patient', 'patient.user_id', 'users.id')
        ->join('title_table', 'title_table.id', '=', 'patient.title_type_id')
        ->leftJoin('patient_type', 'patient_type.id', '=', 'patient.patientType')
        ->leftJoin('insurance_company', 'insurance_company.id', '=', 'patient.insurance_comp_id')
        ->leftJoin('insurance_plane', 'insurance_plane.id', '=', 'patient.insurance_plane_id')
        ->select('patient.user_id', 'title_table.title_name', 'patient.title_type_id', 'surname', 'fname', 'dname','patient.address1', 'patient.address2', 'patient.address3', 'patient.address4', 'caseRefNo', 'dateOfAccident', 'primaryDiagnosisType', 'side', 'dateOfBirth', 'homePhone', 'mobile', 'occupation', 'email', 'maritalStatus', 'religion', 'type_name', 'insur_company_name', 'insurance_plane_name', 'insurance_number', 'notes', 'primaryDiagnosis')
        ->where('users.id', $id)
        ->first();

        return response(['data' => $get_patient]);
    }

    public function add_patient_type(Request $request){
        $type_name = $request->type_name;

        $validator = Validator::make($request->all(), [
            'type_name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $get_type = PatientType::where('type_name', $type_name)->first();

        if(!$get_type){
            $patientType = new PatientType;
            $patientType->type_name = $type_name;
            $patientType->save();

            return response(['success' => 'successfull!']);
        }else{
            return response(['success' => 'already exist!']);
        }
    }

    public function update_patient_type(Request $request){
        $type_id = $request->type_id;
        $type_name = $request->type_name;

        $validator = Validator::make($request->all(), [
            'type_id' => 'required|exists:patient_type,id',
            'type_name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $get_type = PatientType::where('type_name', $type_name)->first();

        if(!$get_type){
            PatientType::where('id', $type_id)
                ->update(['type_name' => $type_name]);

            return response(['success' => 'successfull update!']);
        }else{
            return response(['success' => 'already exist!']);
        }
    }

    public function get_single_patient_type($id){
        $get_all = PatientType::where('id', $id)->select('id', 'type_name')->get();
        return response(['data' => $get_all]);
    }

    public function get_patient_type(){
        $get_all = PatientType::select('id', 'type_name')->get();
        return response(['data' => $get_all]);
    }

    public function add_contact_type(Request $request){
        $type_name = $request->type_name;

        $validator = Validator::make($request->all(), [
            'type_name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $get_type = ContactType::where('type_name', $type_name)->first();

        if(!$get_type){
            $contactType = new ContactType;
            $contactType->type_name = $type_name;
            $contactType->save();

            return response(['success' => 'successfull!']);
        }else{
            return response(['success' => 'already exist!']);
        }
    }

    public function get_contact_type(){
        $get_all = ContactType::select('id', 'type_name')->get();
        return response(['data' => $get_all]);
    }

    public function add_title_type(Request $request){
        $title_name = $request->title_name;

        $validator = Validator::make($request->all(), [
            'title_name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $get_title_name = title::where('title_name', $title_name)->first();

        if(!$get_title_name){
            $title = new title;
            $title->title_name = $title_name;
            $title->save();

            return response(['success' => 'successfull!']);
        }else{
            return response(['success' => 'already exist!']);
        }
    }

    public function get_title_type(){
        $get_all = title::select('id', 'title_name')->get();
        return response(['data' => $get_all]);
    }

    public function import_patient(Request $request){
        set_time_limit(0);
        $extention = $request->file("importfile")->getClientOriginalExtension();
        if($extention == 'xlsx' || $extention == 'csv' || $extention == 'XLSX' || $extention == 'CSV'){

            $import_file = $request->file("importfile");
            Excel::import(new ImportPatient, $import_file);
            return response(['success' => 'successfully imported!']);
            
        }else{
            return response(['message' => 'file should be xlsx or csv!']);
        }
    }

    public function import_titletype(Request $request){
        $extention = $request->file("importfile")->getClientOriginalExtension();
        if($extention == 'xlsx' || $extention == 'csv' || $extention == 'XLSX' || $extention == 'CSV'){

            $import_file = $request->file("importfile");
            Excel::import(new ImportTitleType, $import_file);
            return response(['success' => 'successfully imported!']);
            
        }else{
            return response(['message' => 'file should be xlsx or csv!']);
        }
    }

    public function import_contact_type(Request $request){
        $extention = $request->file("importfile")->getClientOriginalExtension();
        if($extention == 'xlsx' || $extention == 'csv' || $extention == 'XLSX' || $extention == 'CSV'){

            $import_file = $request->file("importfile");
            Excel::import(new ImportContactType, $import_file);
            return response(['success' => 'successfully imported!']);
            
        }else{
            return response(['message' => 'file should be xlsx or csv!']);
        }
    }
}
