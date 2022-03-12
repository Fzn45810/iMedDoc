<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    use HasFactory;
    public $table = "contacts";
    protected $fillable = [
       'id',
       'contact_type_id',
       'title_type_id',
       'surname',
       'fname',
       'dname',
       'entityname',
       'address1',
       'address2',
       'address3',
       'address4',
       'workphone',
       'homephone',
       'mobile',
       'email',
       'website',
       'fax'
    ];
}
