<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactType extends Model
{
    use HasFactory;
    public $table = "contact_type";
    protected $fillable = [
       'id',
       'type_name'
    ];
}
