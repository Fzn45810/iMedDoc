<?php

namespace App\Imports;

use App\Models\AppointDescrip;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportAppointDescrip implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new AppointDescrip([
            'appoint_description' => $row['appoinment_description']
        ]);
    }
}
