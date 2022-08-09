<?php

namespace App\Imports;

use App\Models\IncomeCategory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportIncomeCategory implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new IncomeCategory([
            'category_name' => $row['category_name']
        ]);
    }
}
