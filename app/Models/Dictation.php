<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dictation extends Model
{
    use HasFactory;
    public $table = "dictation";
    protected $fillable = [
       'id',
       'user_id',
       'file_name',
       'dictation_date',
       'dictation_time',
       'duration',
       'status'
    ];
}
