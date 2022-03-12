<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSent extends Model
{
    use HasFactory;
    public $table = "email_sent";
    protected $fillable = [
       'id',
       'email_id',
       'created_at',
       'updated_at'
    ];
}
