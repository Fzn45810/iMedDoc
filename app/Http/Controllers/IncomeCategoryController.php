<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncomeCategory;
use Illuminate\Support\Facades\Validator;
use DB;

class IncomeCategoryController extends Controller
{
    public function create(Request $request){
        $category_name = $request->category_name;
        // Should be 1 or 0
        $is_default = $request->is_default;

        $validator = Validator::make($request->all(), [
            'category_name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $incomecategory = new IncomeCategory;
        $incomecategory->category_name = $category_name;
        $incomecategory->is_default = $is_default;
        $incomecategory->save();

        return response(['success' => 'successfully create!']);
    }

    public function get(){
        $get_all = IncomeCategory::select('id', 'category_name', 'is_default')->get();
        return response(['data' => $get_all]);
    }
}
