<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurgeryProceRela extends Model
{
    use HasFactory;
    public $table = "surgery_proced_relat";
    protected $fillable = [
       'id',
       'surgery_id',
       'procedures_id'
    ];
}
