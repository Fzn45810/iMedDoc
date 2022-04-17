<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lodgement extends Model
{
    use HasFactory;
    public $table = "lodgement";
    protected $fillable = [
       'id',
       'date',
       'bank_id',
       'total_amount',
       'lodgement_memo'
    ];
}
