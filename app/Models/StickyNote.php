<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StickyNote extends Model
{
    use HasFactory;
    public $table = "sticky_notes";
    protected $fillable = [
       'id',
       'belongsto',
       'is_active',
       'notes_description'
    ];
}
