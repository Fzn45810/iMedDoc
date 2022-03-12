<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicLocation extends Model
{
    use HasFactory;
    public $table = "clinic_location";
    protected $fillable = [
       'id',
       'locatio_name',
       'address1',
       'address2',
       'address3',
       'address4',
       'phone',
       'latitude',
       'longitude',
       'income_cate_id'
    ];
}
