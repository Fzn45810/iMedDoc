<?php

namespace App\Imports;

use App\Models\InsurancePlane;
use App\Models\InsuranCompany;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportInsurancePlane implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $get_in_comp = InsuranCompany::where('insur_company_name', $row['ins_comp'])->first();
        if(!is_null($get_in_comp)){
            $in_comp_id = $get_in_comp->id;
        }else{
            $in_comp_id = null;
        }

        return new InsurancePlane([
            'insurance_plane_name' => $row['insurance_plan'],
            'insurance_comp_id' => $in_comp_id
        ]);
    }
}
