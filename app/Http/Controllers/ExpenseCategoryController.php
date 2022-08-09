<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\Validator;
use App\Imports\ImportExpenseCategory;
use Excel;

class ExpenseCategoryController extends Controller
{
    public function create(Request $request){
        $exp_cat_name = $request->exp_cat_name;

        $validator = Validator::make($request->all(), [
            'exp_cat_name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }       

        $expensecategory = new ExpenseCategory;
        $expensecategory->exp_cat_name = $exp_cat_name;
        $expensecategory->save();

        return response(['success' => 'successfully create!']);
    }

    public function update(Request $request){
        $expense_categories_id = $request->expense_categories_id;
        $exp_cat_name = $request->exp_cat_name;

        $validator = Validator::make($request->all(), [
            'expense_categories_id' => 'required|exists:expense_categories,id',
            'exp_cat_name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        ExpenseCategory::where('id', $expense_categories_id)
        ->update(['exp_cat_name' => $exp_cat_name]);

        return response(['success' => 'successfully updated!']);
    }

    public function get_single($id){
        $get_all = ExpenseCategory::where('id', $id)->select('id', 'exp_cat_name')
        ->first();

        return response(['data' => $get_all]);
    }

    public function get(){
        $get_all = ExpenseCategory::select('id', 'exp_cat_name')
        ->get();

        return response(['data' => $get_all]);
    }

    public function import_expensecategory(Request $request){
        $extention = $request->file("importfile")->getClientOriginalExtension();
        if($extention == 'xlsx' || $extention == 'csv' || $extention == 'XLSX' || $extention == 'CSV'){

            $import_file = $request->file("importfile");
            Excel::import(new ImportExpenseCategory, $import_file);
            return response(['success' => 'successfully imported!']);
            
        }else{
            return response(['message' => 'file should be xlsx or csv!']);
        }
    }
}
