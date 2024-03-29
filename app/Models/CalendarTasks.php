<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarTasks extends Model
{
    use HasFactory;
    public $table = "calendar_tasks";
    protected $fillable = [
      'id',
      'appoint_type_id',
      // 'calendar_id',
      'task_time',
      'task_date',
      'task_month',
      'task_year',
      'doctor_id',
      'remind',
      'remind_to',
      'color',
      'task_text',
      'status'
    ];
}
