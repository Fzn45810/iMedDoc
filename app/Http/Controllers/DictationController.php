<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dictation;
use Illuminate\Support\Facades\Validator;
use DB;

class DictationController extends Controller
{
    public function create_dictation(Request $request){
        // date type should be date. formate 2021-12-14
        $user_id = $request->patient_id;
        $dictation_date = $request->dictation_date;
        $dictation_time = $request->dictation_time;
        $duration = $request->duration;
        $file = $request->file("audio");

        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:users,id',
            'audio' => 'required',
            'dictation_date' => 'required',
            'dictation_time' => 'required',
            'duration' => 'required'
        ]);


        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $extention = $request->file("audio")->getClientOriginalExtension();
        $file_name = rand(11111111, 99999999).'.'.$extention;
        $request->file("audio")->move(public_path("audio/"), $file_name);

        $dictation = new Dictation;
        $dictation->user_id = $user_id;
        $dictation->file_name = $file_name;
        $dictation->dictation_date = $dictation_date;
        $dictation->dictation_time = $dictation_time;
        $dictation->duration = $duration;
        $dictation->save();

        return response(['success' => 'successfully create!']);
    }

    public function update_dictation(Request $request){
        $id = $request->id;
        // date type should be date. formate 2021-12-14
        $dictation_date = $request->dictation_date;
        $dictation_time = $request->dictation_time;
        $duration = $request->duration;
        $file = $request->file("audio");

        $validator = Validator::make($request->all(), [
            'audio' => 'required',
            'dictation_date' => 'required',
            'dictation_time' => 'required',
            'duration' => 'required'
        ]);


        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $extention = $request->file("audio")->getClientOriginalExtension();
        $file_name = rand(11111111, 99999999).'.'.$extention;
        $request->file("audio")->move(public_path("audio/"), $file_name);

        $dictation = Dictation::where('id', $id)->first();

        if($dictation){
            Dictation::where('id', $id)
            ->update(['file_name' => $file_name, 'dictation_date' => $dictation_date, 'dictation_time' => $dictation_time, 'duration' => $duration]);

            return response(['success' => 'successfully update!']);
        }else{
            return response(['message' => 'dictation not exist!']);
        }
    }

    public function get_dictation(){
        $get_data = DB::table('dictation')
        ->join('patient', 'patient.user_id', 'dictation.user_id')
        ->select('dictation.id', 'dictation.dictation_date', 'patient.dname', 'patient.surname', 'patient.user_id', 'dictation.dictation_time', 'dictation.duration', 'dictation.status')
        ->get();

        return response(['data' => $get_data]);
    }

    public function get_single_dictation($id){
        $get_data = DB::table('dictation')
        ->join('patient', 'patient.user_id', 'dictation.user_id')
        ->where('dictation.id', $id)
        ->select('dictation.id', 'dictation.dictation_date', 'patient.dname', 'patient.surname', 'patient.user_id', 'dictation.dictation_time', 'dictation.duration', 'dictation.status', 'dictation.file_name')
        ->first();


        $app_name = 'https://demoimed.nextbitsolution.com/audio/';
        
        $object = new \stdClass();
        $object->dictation_id = $get_data->id;
        $object->dictation_date = $get_data->dictation_date;
        $object->dname = $get_data->dname;
        $object->surname = $get_data->surname;
        $object->patient_id = $get_data->user_id;
        $object->dictation_time = $get_data->dictation_time;
        $object->duration = $get_data->duration;
        $object->status = $get_data->status;
        $object->file_name = $app_name . $get_data->file_name;

        return response(['data' => $object]);
    }
}
