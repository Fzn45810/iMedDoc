<?php

namespace App\Imports;

use App\Models\ClinicLocation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportClinicLocation implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ClinicLocation([
            'locatio_name' => $row['location'],
            'address1' => $row['address1'],
            'address2' => $row['address2'],
            'address3' => $row['address3'],
            'address4' => $row['address4'],
            'phone' => $row['phone'],
        ]);
    }
}
