<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrugsDetails extends Model
{
    use HasFactory;
    public $table = "drugs_details";
    protected $fillable = [
       'id',
       'drug_name',
       'dosage',
       'created_at',
       'updated_at'
    ];
}
