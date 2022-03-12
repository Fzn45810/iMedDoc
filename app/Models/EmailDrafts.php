<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailDrafts extends Model
{
    use HasFactory;
    public $table = "email_drafts";
    protected $fillable = [
       'id',
       'email_id',
       'created_at',
       'updated_at'
    ];
}
