<?php

namespace App\Imports;

use App\Models\InsuranCompany;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportInsurancomp implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new InsuranCompany([
            'insur_company_name' => $row['company'],
            'address1' => $row['address1'],
            'address2' => $row['address2'],
            'address3' => $row['address3'],
            'address4' => $row['address4'],
            'phone' => $row['phone'],
        ]);
    }
}
