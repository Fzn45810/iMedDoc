<?php

namespace App\Imports;

use App\Models\PatientModel;
use App\Models\title;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportPatient implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $get_title = title::where('title_name', $row['title'])->first();
        if(!is_null($get_title)){
            $title_id = $get_title->id;
        }else{
            $title_id = null;
        }

        $get_dname = $row['display_name'];
        $name_array = explode(' ', $get_dname);
        if(count($name_array) > 1){
            if(count($name_array) == 1){
                $fname = $name_array[0];
                $surname = $name_array[0];
            }else{
                $fname = $name_array[1];
                $surname = $name_array[0];
            }
        }else{
            $surname = null;
            $fname = null;
        }

        $password = mt_rand(100000,999999);

        $user = new User;
        $user->password = bcrypt($password);
        $user->fname = $fname;
        $user->save();

        return new PatientModel([
            'user_id' => $user->id,
            'title_type_id' => $title_id,
            'surname' => $surname,
            'dname' => $row['display_name'],
            'homePhone' => $row['phone'],
            'mobile' => $row['mobile'],
            'address1' => $row['address1'],
            'address2' => $row['address2']
        ]);
    }
}
