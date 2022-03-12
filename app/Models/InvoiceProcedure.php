<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceProcedure extends Model
{
    use HasFactory;
    public $table = "invoice_proced_relat";
    protected $fillable = [
       'id',
       'invoice_id',
       'procedures_id'
    ];
}
