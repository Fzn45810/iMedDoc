<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class receipt extends Model
{
    use HasFactory;
    public $table = "receipt";
    protected $fillable = [
       'id',
       'date',
       'received_from',
       'mode_of_payment',
       'cheque_date',
       'cheque_no',
       'bank_name',
       'patient_id',
       'third_party',
       'rec_insur_comp_id',
       'r_tax',
       'waived',
       'payment',
       'receipt_memo'
    ];
}
