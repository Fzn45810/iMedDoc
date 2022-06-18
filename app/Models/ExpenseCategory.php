<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;
    public $table = "expense_categories";
    protected $fillable = [
       'id',
       'exp_cat_name',
       'created_at',
       'updated_at'
    ];
}
