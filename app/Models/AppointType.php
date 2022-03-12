<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointType extends Model
{
    use HasFactory;
    public $table = "appoint_type";
    protected $fillable = [
       'id',
       'appoint_name'
    ];
}
