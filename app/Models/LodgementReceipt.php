<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LodgementReceipt extends Model
{
    use HasFactory;
    public $table = "lodgement_receipt_rel";
    protected $fillable = [
       'id',
       'lodgement_id',
       'receipt_id'
    ];
}
