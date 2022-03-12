<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarSurgery extends Model
{
    use HasFactory;
    public $table = "calendar_surgery";
    protected $fillable = [
       'id',
       'appoint_type_id',
       'calendar_id',
       'patient_id',
       'hospital_id',
       'surgery_from',
       'procedure1_id',
       'procedure2_id',
       'procedure3_id',
       'doctor_id',
       'anesthetist',
       'surgery_time',
       'surgery_date',
       'admission_date',
       'surgery_note',
       'surgery_temp'
    ];
}
