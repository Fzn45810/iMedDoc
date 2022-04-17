<?php

namespace App\Http\Controllers;
use App\Models\DankDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BankController extends Controller
{
    public function create(Request $request){
        $bank_name = $request->bank_name;
        $account_no = $request->account_no;
        $opening_balance = $request->opening_balance;

        $validator = Validator::make($request->all(), [
            'bank_name' => 'required',
            'account_no' => 'required',
            'opening_balance' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $bank_details = new DankDetails;
        $bank_details->bank_name = $bank_name;
        $bank_details->account_no = $account_no;
        $bank_details->opening_balance = $opening_balance;
        $bank_details->save();

        return response(['success' => 'successfully create!']);
    }

    public function update(Request $request){
        $id = $request->id;
        $bank_name = $request->bank_name;
        $account_no = $request->account_no;
        $opening_balance = $request->opening_balance;

        $bank_details = DankDetails::where('id', $id)->first();

        if($bank_details){
            DankDetails::where('id', $id)
            ->update(['bank_name' => $bank_name, 'account_no' => $account_no, 'opening_balance' => $opening_balance]);
            return response(['success' => 'successfully updated!']);

        }else{
            return response(['message' => 'bank not exist!']);
        }
    }

    public function get(){
        $get_all = DankDetails::
        select('id', 'bank_name', 'account_no', 'opening_balance')->get();
        return response(['data' => $get_all]);

    }

    public function single_get($id){
        $get_all = DankDetails::where('id', $id)
        ->select('id', 'bank_name', 'account_no', 'opening_balance')->first();
        return response(['data' => $get_all]);

    }
}
