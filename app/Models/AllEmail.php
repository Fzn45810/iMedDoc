<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllEmail extends Model
{
    use HasFactory;
    public $table = "all_email";
    protected $fillable = [
       'id',
       'to',
       'from',
       'to_name',
       'from_name',
       'subject',
       'email_text',
       'cc',
       'bcc',
       'created_at',
       'updated_at'
    ];
}
