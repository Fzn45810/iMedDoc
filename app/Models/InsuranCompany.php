<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranCompany extends Model
{
    use HasFactory;
    public $table = "insurance_company";
    protected $fillable = [
       'id',
       'insur_company_name',
       'address1',
       'address2',
       'address3',
       'address4',
       'phone',
       'insurance_form_name',
       'mode_of_paymen',
       'deduct_tax'
    ];
}
