<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    use HasFactory;
    public $table = "expenses";
    protected $fillable = [
       'id',
       'expenses_date',
       'expenses_amount',
       'expenses_category',
       'expenses_payment_mode',
       'expens_bank_name',
       'expens_cheque_no',
       'expens_cheque_date',
       'expens_card_type',
       'expens_card_name',
       'expens_card_no',
       'expens_card_expi_date',
       'expens_refrence_no',
       'expens_ref_bank_name',
       'expens_details',
       'expens_file_one',
       'expens_file_two',
       'expens_file_three'
    ];
}
