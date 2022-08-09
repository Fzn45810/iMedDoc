<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sms_setting extends Model
{
    use HasFactory;
    public $table = "sms_settings";
    protected $fillable = [
       'id',
       'sms_title',
       'sms_content',
       'sms_enable'
    ];
}
