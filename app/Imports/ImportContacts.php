<?php

namespace App\Imports;

use App\Models\Contacts;
use App\Models\ContactType;
use App\Models\title;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportContacts implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $get_contacttype = ContactType::where('type_name', $row['contact_type'])->first();
        if(!is_null($get_contacttype)){
            $contacttype_id = $get_contacttype->id;
        }else{
            $contacttype_id = null;
        }

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

        return new Contacts([
            'contact_type_id' => $contacttype_id,
            'title_type_id' => $title_id,
            'surname' => $surname,
            'dname' => $row['display_name'],
            'entityname' => $row['entity_name'],
            'workphone' => $row['phone1'],
            'homephone' => $row['phone2'],
            'mobile' => $row['mobile'],
            'email' => $row['email'],
            'fax' => $row['fax'],
            'address1' => $row['address1'],
            'address2' => $row['address2'],
            'address2' => $row['address2'],
            'address3' => $row['address3']
        ]);
    }
}
