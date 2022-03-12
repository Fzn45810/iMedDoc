<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointDescrip extends Model
{
    use HasFactory;
    public $table = "appoint_descrip";
    protected $fillable = [
       'id',
       'appoint_description',
       'procedures_id',
       'color',
       'appointments'
    ];
}
