<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Form;
use Illuminate\Support\Facades\Validator;
use DB;

class FormController extends Controller
{
    public function create(Request $request){
        // Should be 1 or 0
        $status = $request->status;
        $form_name = $request->form_name;
        // Should be 1 or 0
        $is_default = $request->is_default;
        $form_type = $request->form_type;

        $validator = Validator::make($request->all(), [
            'status' => 'required',
            'form_name' => 'required',
            'form_type' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        if(trim($form_type) == 'Admission Forms' || trim($form_type) == 'Requesting Forms' || trim($form_type) == 'Insurance Forms'){

            $form = new Form;
            $form->status = $status;
            $form->form_name = $form_name;
            $form->is_default = $is_default;
            $form->form_type = $form_type;
            $form->save();

            return response(['success' => 'successfully create!']);

        }else{
            return response(['error' => 'form_type should be Admission Forms or Requesting Forms or Insurance Forms']);
        }
    }

    public function get(){
        $get_all = Form::get();
        foreach ($get_all as $key => $value) {
            $seprate[$value->form_type][] = ['id'=>$value->id, 'sataus'=>$value->status, 'name'=>$value->form_name, 'is_default'=>$value->is_default];
        }
        return response(['data' => $seprate]);
    }
}
