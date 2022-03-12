<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class title extends Model
{
    use HasFactory;
    public $table = "title_table";
    protected $fillable = [
       'id',
       'title_name'
    ];
}
