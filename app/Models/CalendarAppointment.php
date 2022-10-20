<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarAppointment extends Model
{
    use HasFactory;
    public $table = "calendar_appointment";
    protected $fillable = [
       'id',
       'appoint_type_id',
       // 'calendar_id',
       'patient_id',
       'description_id',
       'location_id',
       'doctor_id',
       'appoint_time',
       'clinic_physio',
       'appoint_date',
       'appoint_month',
       'appoint_year',
       'appoint_notes',
       'appoint_temp',
       'status'
    ];
}
