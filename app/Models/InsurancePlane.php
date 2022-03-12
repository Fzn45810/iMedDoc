<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsurancePlane extends Model
{
    use HasFactory;
    public $table = "insurance_plane";
    protected $fillable = [
       'id',
       'insurance_plane_name',
       'insurance_comp_id'
    ];
}
