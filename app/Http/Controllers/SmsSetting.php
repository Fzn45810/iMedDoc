<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\sms_setting;
use Illuminate\Support\Facades\Validator;
use DB;

class SmsSetting extends Controller
{
    public function create(Request $request){
        $sms_title = $request->sms_title;
        $sms_content = $request->sms_content;
        $sms_enable = $request->sms_enable;

        $validator = Validator::make($request->all(), [
            'sms_title' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $smssetting = new sms_setting;
        $smssetting->sms_title = $sms_title;
        $smssetting->sms_content = $sms_content;
        $smssetting->sms_enable = $sms_enable;
        $smssetting->save();

        return response(['success' => 'successfully create!']);
    }

    public function update(Request $request){
        $id = $request->id;
        $sms_title = $request->sms_title;
        $sms_content = $request->sms_content;
        $sms_enable = $request->sms_enable;

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:sms_settings,id'
            'sms_title' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        sms_setting::where('id', $id)
        ->update(['sms_title' => $sms_title, 'sms_content' => $sms_content, 'sms_enable' => $sms_enable]);

        return response(['success' => 'successfully updated!']);
    }

    public function get_single($id){
        $get_all = DB::table('sms_settings')
        ->where('sms_settings.id', $id)
        ->select('sms_settings.id', 'sms_title', 'sms_content', 'sms_enable')
        ->first();
        return response(['data' => $get_all]);
    }

    public function get(){
        $get_all = DB::table('sms_settings')
        ->select('sms_settings.id', 'sms_title', 'sms_content', 'sms_enable')
        ->get();
        return response(['data' => $get_all]);
    }
}
