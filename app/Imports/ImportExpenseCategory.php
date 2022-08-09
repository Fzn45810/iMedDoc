<?php

namespace App\Imports;

use App\Models\ExpenseCategory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportExpenseCategory implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ExpenseCategory([
            'exp_cat_name' => $row['category_name']
        ]);
    }
}
