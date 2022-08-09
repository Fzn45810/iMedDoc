<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncomeCategory;
use Illuminate\Support\Facades\Validator;
use App\Imports\ImportIncomeCategory;
use Excel;
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

    public function update(Request $request){
        $income_category_id = $request->income_category_id;
        $category_name = $request->category_name;
        // Should be 1 or 0
        $is_default = $request->is_default;

        $validator = Validator::make($request->all(), [
            'income_category_id' => 'required|exists:income_category,id',
            'category_name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        IncomeCategory::where('id', $income_category_id)
        ->update(['category_name' => $category_name, 'is_default' => $is_default]);

        return response(['success' => 'successfully updated!']);
    }

    public function get_single($id){
        $get_all = IncomeCategory::where('id', $id)->select('id', 'category_name', 'is_default')->first();
        return response(['data' => $get_all]);
    }

    public function get(){
        $get_all = IncomeCategory::select('id', 'category_name', 'is_default')->get();
        return response(['data' => $get_all]);
    }

    public function import_incomecategory(Request $request){
        $extention = $request->file("importfile")->getClientOriginalExtension();
        if($extention == 'xlsx' || $extention == 'csv' || $extention == 'XLSX' || $extention == 'CSV'){

            $import_file = $request->file("importfile");
            Excel::import(new ImportIncomeCategory, $import_file);
            return response(['success' => 'successfully imported!']);
            
        }else{
            return response(['message' => 'file should be xlsx or csv!']);
        }
    }
}
