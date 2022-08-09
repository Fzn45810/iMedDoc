<?php

namespace App\Imports;

use App\Models\Procedures;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportProcedures implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Procedures([
            'procedure_name' => $row['procedure_name'],
            'code' => $row['procedure_code'],
            'rate' => $row['rate']
        ]);
    }
}
