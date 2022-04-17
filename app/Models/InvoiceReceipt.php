<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceReceipt extends Model
{
    use HasFactory;
    public $table = "invoice_receipt_relat";
    protected $fillable = [
       'id',
       'invoice_id',
       'receipt_id',
       'relat_r_tax',
       'relat_waived',
       'relat_payment'
    ];
}
