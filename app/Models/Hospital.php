<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;
    public $table = "hospital";
    protected $fillable = [
       'id',
       'hospital_name',
       'type',
       'address1',
       'address2',
       'address3',
       'address4',
       'phone',
       'fax',
       'hospital_email',
       'website',
       'latitude',
       'longitude',
       'forms_id',
       'income_category_id'
    ];
}
