<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaitingList extends Model
{
    use HasFactory;
    public $table = "waiting_list";
    protected $fillable = [
        'id',
        'patient_id',
        'waitingFrom',
        'waitingFor',
        'procedures_id',
        'appoint_id',
        'priority',
        'notes',
        'status'
    ];
}
