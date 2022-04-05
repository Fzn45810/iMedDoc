<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    public $table = "invoice";
    protected $fillable = [
       'id',
       'bill_to',
       'date',
       'income_category_id',
       'insurance_company_id',
       'insurance_number',
       'patient_id',
       'solicitor_id',
       'sub_total',
       'tax',
       'tax_percentage',
       'net_total',
       'paid',
       'memo'
    ];
}
