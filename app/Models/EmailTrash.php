<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTrash extends Model
{
    use HasFactory;
    public $table = "email_trash";
    protected $fillable = [
       'id',
       'email_id',
       'created_at',
       'updated_at'
    ];
}
