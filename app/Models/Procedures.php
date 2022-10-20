<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procedures extends Model
{
   use HasFactory;
   public $table = "procedures";
   protected $fillable = [
      'id',
      'procedure_name',
      'code',
      'rate',
      'template',
      'color_code',
      'duration_h',
      'duration_m'
   ];
}
