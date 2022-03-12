<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceProcedure extends Model
{
    use HasFactory;
    public $table = "insurance_proced_relat";
    protected $fillable = [
       'id',
       'insurance_id',
       'procedures_id',
       'rates'
    ];
}
