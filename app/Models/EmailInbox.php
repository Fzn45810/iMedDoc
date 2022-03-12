<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailInbox extends Model
{
    use HasFactory;
    public $table = "email_inbox";
    protected $fillable = [
       'id',
       'email_id',
       'isread',
       'created_at',
       'updated_at'
    ];
}
