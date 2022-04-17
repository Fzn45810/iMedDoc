<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DankDetails extends Model
{
    use HasFactory;
    public $table = "bank_details";
    protected $fillable = [
       'id',
       'bank_name',
       'account_no',
       'opening_balance',
       'created_at',
       'updated_at'
    ];
}
