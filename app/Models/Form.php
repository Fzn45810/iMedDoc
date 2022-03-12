<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;
    public $table = "form";
    protected $fillable = [
       'id',
       'status',
       'form_name',
       'is_default',
       'form_type'
    ];
}
