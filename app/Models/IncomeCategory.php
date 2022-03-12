<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeCategory extends Model
{
    use HasFactory;
    public $table = "income_category";
    protected $fillable = [
       'id',
       'category_name',
       'is_default'
    ];
}
