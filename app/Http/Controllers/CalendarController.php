<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calendar;
use App\Models\CalendarAppointment;
use App\Models\CalendarSurgery;
use App\Models\CalendarTasks;
use App\Models\SurgeryProceRela;
use Illuminate\Support\Facades\Validator;
use DB;

class CalendarController extends Controller
{
    public function create(Request $request){

        $description = $request->description;
        // date type should be date. formate 2021-12-14
        $date  = $request->date;
        // boolean should be 1 or 0
        $holiday = $request->holiday;

        $validator = Validator::make($request->all(), [
            'description' => 'required',
            'date' => 'required',
            'holiday' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $calendar = Calendar::where('date', $date)->first();

        if(!$calendar){
            $wiatinglist = new Calendar;
            $wiatinglist->description = $description;
            $wiatinglist->date = $date;
            $wiatinglist->holiday = $holiday;
            $wiatinglist->save();

            return response(['success' => 'successfully create!']);
        }else{
            return response(['message' => 'date already exists!']);
        }
    }

    public function GetCalendar(){
        $getcalendar = DB::table('calendar')
        ->select('id', 'description', 'date', 'holiday')->get();
        return response(['data' => $getcalendar]);
    }

    public function CreateAppointment(Request $request){

        $appoint_type_id = $request->appoint_type_id;
        $calendar_id = $request->calendar_id;
        $patient_id  = $request->patient_id;
        $description_id = $request->description_id;
        $location_id = $request->location_id;
        $doctor_id  = $request->doctor_id;
        $appoint_time = $request->appoint_time;
        $clinic_physio = $request->clinic_physio;
        // date type should be date. formate 2021-12-14
        $appoint_date  = $request->appoint_date;
        $appoint_notes = $request->appoint_notes;
        $appoint_temp = $request->appoint_temp;

        $validator = Validator::make($request->all(), [
            'appoint_type_id' => 'required|exists:appoint_type,id',
            'calendar_id' => 'required|exists:calendar,id',
            'patient_id' => 'required|exists:patient,id',
            'description_id' => 'required|exists:appoint_descrip,id',
            'location_id' => 'required|exists:clinic_location,id',
            'doctor_id' => 'required|exists:doctor,id',
            'appoint_time' => 'required',
            'appoint_date' => 'required|date',
            'clinic_physio' => 'required',
            'appoint_temp' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $calendarappoint = new CalendarAppointment;
        $calendarappoint->appoint_type_id = $appoint_type_id;
        $calendarappoint->calendar_id = $calendar_id;
        $calendarappoint->patient_id = $patient_id;
        $calendarappoint->description_id = $description_id;
        $calendarappoint->location_id = $location_id;
        $calendarappoint->doctor_id = $doctor_id;
        $calendarappoint->appoint_time = $appoint_time;
        $calendarappoint->clinic_physio = $clinic_physio;
        // date type should be date. formate 2021-12-14
        $calendarappoint->appoint_date = $appoint_date;
        $calendarappoint->appoint_notes = $appoint_notes;
        $calendarappoint->appoint_temp = $appoint_temp;
        $calendarappoint->save();

        return response(['success' => 'successfully create!']);
    }

    public function get_calendar_appoint(){
        $getappoint = DB::table('calendar_appointment')
        ->join('appoint_type', 'appoint_type.id', '=', 'calendar_appointment.appoint_type_id')
        ->join('calendar', 'calendar.id', '=', 'calendar_appointment.calendar_id')
        ->join('patient', 'patient.id', '=', 'calendar_appointment.patient_id')
        ->join('appoint_descrip', 'appoint_descrip.id', '=', 'calendar_appointment.description_id')
        ->join('clinic_location', 'clinic_location.id', '=', 'calendar_appointment.location_id')
        ->join('doctor', 'doctor.id', '=', 'calendar_appointment.doctor_id')
        ->select('calendar_appointment.id', 'appoint_time', 'dname', 'mobile', 'appoint_description', 'locatio_name', 'notes', 'status')
        ->get();

        return response(['data' => $getappoint]);
    }

    public function update_appoint_sataus($id, $value){
        DB::table('calendar_appointment')
        ->where('id', '=', $id)
        ->update(['status' => $value]);

        $getappoint = DB::table('calendar_appointment')
        ->join('appoint_type', 'appoint_type.id', '=', 'calendar_appointment.appoint_type_id')
        ->join('calendar', 'calendar.id', '=', 'calendar_appointment.calendar_id')
        ->join('patient', 'patient.id', '=', 'calendar_appointment.patient_id')
        ->join('appoint_descrip', 'appoint_descrip.id', '=', 'calendar_appointment.description_id')
        ->join('clinic_location', 'clinic_location.id', '=', 'calendar_appointment.location_id')
        ->join('doctor', 'doctor.id', '=', 'calendar_appointment.doctor_id')
        ->select('calendar_appointment.id', 'appoint_time', 'dname', 'mobile', 'appoint_description', 'locatio_name', 'notes', 'status')
        ->get();

        return response(['data' => $getappoint]);
    }

    public function create_surgery(Request $request){
        $appoint_type_id = $request->appoint_type_id;
        $calendar_id = $request->calendar_id;
        $patient_id  = $request->patient_id;
        $hospital_id = $request->hospital_id;
        $surgery_from = $request->surgery_from;
        $procedure1_id  = $request->procedure1_id;
        $procedure2_id = $request->procedure2_id;
        $procedure3_id = $request->procedure3_id;
        $doctor_id  = $request->doctor_id;
        $anesthetist = $request->anesthetist;
        $surgery_time = $request->surgery_time;
        // date type should be date. formate 2021-12-14
        $surgery_date = $request->surgery_date;
        // date type should be date. formate 2021-12-14
        $admission_date  = $request->admission_date;
        $surgery_note = $request->surgery_note;
        $surgery_temp = $request->surgery_temp;

        $validator = Validator::make($request->all(), [
            'appoint_type_id' => 'required|exists:appoint_type,id',
            'calendar_id' => 'required|exists:calendar,id',
            'patient_id' => 'required|exists:patient,id',
            'hospital_id' => 'required|exists:hospital,id',
            'surgery_from' => 'required',
            'procedure1_id' => 'required|exists:procedures,id',
            'procedure2_id' => 'required|exists:procedures,id',
            'procedure3_id' => 'required|exists:procedures,id',
            'doctor_id' => 'required|exists:doctor,id',
            'anesthetist' => 'required',
            'surgery_time' => 'required',
            'surgery_date' => 'required',
            'admission_date' => 'required',
            'surgery_note' => 'required',
            'surgery_temp' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $calendarsurgery = new CalendarSurgery;
        $calendarsurgery->appoint_type_id = $appoint_type_id;
        $calendarsurgery->calendar_id = $calendar_id;
        $calendarsurgery->patient_id = $patient_id;
        $calendarsurgery->hospital_id = $hospital_id;
        $calendarsurgery->surgery_from = $surgery_from;
        $calendarsurgery->doctor_id = $doctor_id;
        $calendarsurgery->anesthetist = $anesthetist;
        $calendarsurgery->surgery_time = $surgery_time;
        // date type should be date. formate 2021-12-14
        $calendarsurgery->surgery_date = $surgery_date;
        $calendarsurgery->admission_date = $admission_date;
        $calendarsurgery->surgery_note = $surgery_note;
        $calendarsurgery->surgery_temp = $surgery_temp;
        $calendarsurgery->save();

        $surgery_proce_rela1 = new SurgeryProceRela;
        $surgery_proce_rela1->surgery_id = $calendarsurgery->id;
        $surgery_proce_rela1->procedures_id = $procedure1_id;
        $surgery_proce_rela1->save();

        $surgery_proce_rela2 = new SurgeryProceRela;
        $surgery_proce_rela2->surgery_id = $calendarsurgery->id;
        $surgery_proce_rela2->procedures_id = $procedure2_id;
        $surgery_proce_rela2->save();

        $surgery_proce_rela3 = new SurgeryProceRela;
        $surgery_proce_rela3->surgery_id = $calendarsurgery->id;
        $surgery_proce_rela3->procedures_id = $procedure3_id;
        $surgery_proce_rela3->save();


        return response(['success' => 'successfully create!']);
    }

    public function get_surgery(){
        // $getsurgery = DB::table('calendar_surgery')
        // ->join('appoint_type', 'appoint_type.id', '=', 'calendar_surgery.appoint_type_id')
        // ->join('calendar', 'calendar.id', '=', 'calendar_surgery.calendar_id')
        // ->join('patient', 'patient.id', '=', 'calendar_surgery.patient_id')
        // ->join('hospital', 'hospital.id', '=', 'calendar_surgery.hospital_id')
        // ->join('surgery_proced_relat', 'surgery_proced_relat.surgery_id', '=', 'calendar_surgery.id')
        // ->join('procedures', 'procedures.id', '=', 'surgery_proced_relat.procedures_id')
        // ->get();

        $getsurgery = DB::table('calendar_surgery')
        ->join('appoint_type', 'appoint_type.id', '=', 'calendar_surgery.appoint_type_id')
        ->join('calendar', 'calendar.id', '=', 'calendar_surgery.calendar_id')
        ->join('patient', 'patient.id', '=', 'calendar_surgery.patient_id')
        ->join('hospital', 'hospital.id', '=', 'calendar_surgery.hospital_id')
        ->select('calendar_surgery.id', 'surgery_time', 'dname', 'mobile', 'description', 'hospital_name', 'notes', 'status')
        ->get();

        return response(['data' => $getsurgery]);
    }

    public function update_surgery_sataus($id, $value){
        DB::table('calendar_surgery')
        ->where('id', '=', $id)
        ->update(['status' => $value]);

        $getsurgery = DB::table('calendar_surgery')
        ->join('appoint_type', 'appoint_type.id', '=', 'calendar_surgery.appoint_type_id')
        ->join('calendar', 'calendar.id', '=', 'calendar_surgery.calendar_id')
        ->join('patient', 'patient.id', '=', 'calendar_surgery.patient_id')
        ->join('hospital', 'hospital.id', '=', 'calendar_surgery.hospital_id')
        ->select('calendar_surgery.id', 'surgery_time', 'dname', 'mobile', 'description', 'hospital_name', 'notes', 'status')
        ->get();

        return response(['data' => $getsurgery]);
    }

    public function create_tasks(Request $request){
        $appoint_type_id = $request->appoint_type_id;
        $calendar_id = $request->calendar_id;
        $task_time  = $request->task_time;
        // date type should be date. formate 2021-12-14
        $task_date = $request->task_date;
        $doctor_id  = $request->doctor_id;
        $remind = $request->remind;
        $remind_to  = $request->remind_to;
        $color = $request->color;
        $task_text = $request->task_text;

        $validator = Validator::make($request->all(), [
            'appoint_type_id' => 'required|exists:appoint_type,id',
            'calendar_id' => 'required|exists:calendar,id',
            'task_time' => 'required',
            'task_date' => 'required',
            'doctor_id' => 'required|exists:doctor,id',
            'remind' => 'required',
            'remind_to' => 'required',
            'color' => 'required',
            'task_text' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $calendartasks = new CalendarTasks;
        $calendartasks->appoint_type_id = $appoint_type_id;
        $calendartasks->calendar_id = $calendar_id;
        $calendartasks->task_time = $task_time;
        // date type should be date. formate 2021-12-14
        $calendartasks->task_date = $task_date;
        $calendartasks->doctor_id = $doctor_id;
        $calendartasks->remind = $remind;
        $calendartasks->remind_to = $remind_to;
        $calendartasks->color = $color;
        $calendartasks->task_text = $task_text;
        $calendartasks->save();

        return response(['success' => 'successfully create!']);
    }

    public function get_tasks(){

        $gettasks = DB::table('calendar_tasks')
        ->join('appoint_type', 'appoint_type.id', '=', 'calendar_tasks.appoint_type_id')
        ->join('calendar', 'calendar.id', '=', 'calendar_tasks.calendar_id')
        ->join('doctor', 'doctor.id', '=', 'calendar_tasks.doctor_id')
        ->select('calendar_tasks.id', 'task_time', 'description', 'status')
        ->get();

        return response(['data' => $gettasks]);
    }

    public function update_tasks_sataus($id, $value){
        DB::table('calendar_tasks')
        ->where('id', '=', $id)
        ->update(['status' => $value]);

        $gettasks = DB::table('calendar_tasks')
        ->join('appoint_type', 'appoint_type.id', '=', 'calendar_tasks.appoint_type_id')
        ->join('calendar', 'calendar.id', '=', 'calendar_tasks.calendar_id')
        ->join('doctor', 'doctor.id', '=', 'calendar_tasks.doctor_id')
        ->select('calendar_tasks.id', 'task_time', 'description', 'status')
        ->get();

        return response(['data' => $gettasks]);
    }

    public function getDateCalendar(Request $request){
        $date = $request->date;

        $validator = Validator::make($request->all(), [
            'date' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $appointmentdata = DB::table('calendar')
        ->join('calendar_appointment', 'calendar_appointment.calendar_id', '=', 'calendar.id')
        ->where('calendar.date', '=', $date)
        ->get();

        $appointmentdata = DB::table('calendar')
        ->join('calendar_appointment', 'calendar_appointment.calendar_id', '=', 'calendar.id')
        ->where('calendar.date', '=', $date)
        ->get();

        $surgerydata = DB::table('calendar')
        ->join('calendar_surgery', 'calendar_surgery.calendar_id', '=', 'calendar.id')
        ->where('calendar.date', '=', $date)
        ->get();

        $tasksdata = DB::table('calendar')
        ->join('calendar_tasks', 'calendar_tasks.calendar_id', '=', 'calendar.id')
        ->where('calendar.date', '=', $date)
        ->get();

        $alldata['appointment'] = $appointmentdata;
        $alldata['surgery'] = $surgerydata;
        $alldata['tasks'] = $tasksdata;

        // ->select('id', 'description', 'date', 'holiday')->get();
        return response(['data' => $alldata]);
    }
}
