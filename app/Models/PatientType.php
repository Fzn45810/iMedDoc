<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientType extends Model
{
    use HasFactory;
    public $table = "patient_type";
    protected $fillable = [
       'id',
       'type_name'
    ];
}
