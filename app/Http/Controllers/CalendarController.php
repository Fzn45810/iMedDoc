<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calendar;
use App\Models\CalendarAppointment;
use App\Models\CalendarSurgery;
use App\Models\CalendarTasks;
use App\Models\SurgeryProceRela;
use App\Models\Procedures;
use Illuminate\Support\Facades\Validator;
use DB;
use Carbon\Carbon;

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
        
        $day = Carbon::createFromFormat('Y-m-d', $date);
        $month_name = $day->format('F');
        $year_name = $day->format('Y');

        $calendar = Calendar::where('date', $date)->first();

        if(!$calendar){
            $wiatinglist = new Calendar;
            $wiatinglist->description = $description;
            $wiatinglist->date = $date;
            $wiatinglist->calendar_month = $month_name;
            $wiatinglist->calendar_year = $year_name;
            $wiatinglist->holiday = $holiday;
            $wiatinglist->save();

            return response(['success' => 'successfully create!']);
        }else{
            return response(['message' => 'date already exists!']);
        }
    }

    public function get_single_calendar($id){
        $getcalendar = DB::table('calendar')
        ->where('id', $id)->get();
        return response(['data' => $getcalendar]);
    }

    public function calendar_update(Request $request){
        $id = $request->id;
        $description = $request->description;
        // date type should be date. formate 2021-12-14
        $date  = $request->date;
        // boolean should be 1 or 0
        $holiday = $request->holiday;

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:calendar,id',
            'description' => 'required',
            'date' => 'required',
            'holiday' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        
        $day = Carbon::createFromFormat('Y-m-d', $date);
        $month_name = $day->format('F');
        $year_name = $day->format('Y');

        $calendar_id = Calendar::where('id', $id)->first();
        $calendar_date = Calendar::where('date', $date)->first();

        if(!!$calendar_id){
            if(!$calendar_date){
                Calendar::where('id', $id)
                ->update(['description' => $description, 'date' => $date, 'calendar_month' => $month_name, 'calendar_year' => $year_name ,'holiday' => $holiday]);
            }else{
                Calendar::where('id', $id)
                ->where('date', $date)
                ->update(['description' => $description, 'holiday' => $holiday]);
                return response(['success' => 'successfully updated!']);
            }

        }else{
            return response(['message' => 'calander not found!']);
        }
        
        return response(['success' => 'successfully updated!']);
    }

    public function get_calendar(){
        $getcalendar = DB::table('calendar')
        ->select('id', 'description', 'date', 'holiday')->get();
        return response(['data' => $getcalendar]);
    }

    public function create_appointment(Request $request){

        $appoint_type_id = $request->appoint_type_id;
        // $calendar_id = $request->calendar_id;
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
            // 'calendar_id' => 'required|exists:calendar,id',
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

        $day = Carbon::createFromFormat('Y-m-d', $appoint_date);
        $month_name = $day->format('F');
        $year_name = $day->format('Y');

        $calendarappoint = new CalendarAppointment;
        $calendarappoint->appoint_type_id = $appoint_type_id;
        // $calendarappoint->calendar_id = $calendar_id;
        $calendarappoint->patient_id = $patient_id;
        $calendarappoint->description_id = $description_id;
        $calendarappoint->location_id = $location_id;
        $calendarappoint->doctor_id = $doctor_id;
        $calendarappoint->appoint_time = $appoint_time;
        $calendarappoint->clinic_physio = $clinic_physio;
        // date type should be date. formate 2021-12-14
        $calendarappoint->appoint_date = $appoint_date;
        $calendarappoint->appoint_month = $month_name;
        $calendarappoint->appoint_year = $year_name;
        $calendarappoint->appoint_notes = $appoint_notes;
        $calendarappoint->appoint_temp = $appoint_temp;
        $calendarappoint->save();

        return response(['success' => 'successfully create!']);
    }

    public function update_appointment(Request $request){
        $id = $request->id;
        $appoint_type_id = $request->appoint_type_id;
        // $calendar_id = $request->calendar_id;
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
            'id' => 'required|exists:calendar_appointment,id',
            'appoint_type_id' => 'required|exists:appoint_type,id',
            // 'calendar_id' => 'required|exists:calendar,id',
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

        $day = Carbon::createFromFormat('Y-m-d', $appoint_date);
        $month_name = $day->format('F');
        $year_name = $day->format('Y');

        CalendarAppointment::where('id', $id)
        ->update(['appoint_type_id' => $appoint_type_id, 'patient_id' => $patient_id,
                'description_id' => $description_id, 'location_id' => $location_id,
                'doctor_id' => $doctor_id, 'appoint_time' => $appoint_time,
                'clinic_physio' => $clinic_physio, 'appoint_date' => $appoint_date,
                'appoint_month' => $month_name, 'appoint_year' => $year_name,
                'appoint_notes' => $appoint_notes, 'appoint_temp' => $appoint_temp]);

        return response(['success' => 'successfully updated!']);
    }

    public function delete_appointment($id){
        $appointment = CalendarAppointment::find($id);
        if($appointment){
            $appointment->delete();
            return response(['success' => 'successfully deleted!']);
        }else{
            return response(['message' => 'contact not found!']);
        }
    }

    public function get_calendar_appoint(){
        $getappoint = DB::table('calendar_appointment')
        ->join('appoint_type', 'appoint_type.id', '=', 'calendar_appointment.appoint_type_id')
        // ->join('calendar', 'calendar.id', '=', 'calendar_appointment.calendar_id')
        ->join('patient', 'patient.id', '=', 'calendar_appointment.patient_id')
        ->join('appoint_descrip', 'appoint_descrip.id', '=', 'calendar_appointment.description_id')
        ->join('clinic_location', 'clinic_location.id', '=', 'calendar_appointment.location_id')
        ->join('doctor', 'doctor.id', '=', 'calendar_appointment.doctor_id')
        ->select('calendar_appointment.id', 'appoint_time', 'dname', 'mobile', 'appoint_description', 'locatio_name', 'notes', 'status', 'appoint_date')
        ->get();

        return response(['data' => $getappoint]);
    }

    public function get_single_calendar_appoint($id){
        $getappoint = DB::table('calendar_appointment')
        ->join('appoint_type', 'appoint_type.id', '=', 'calendar_appointment.appoint_type_id')
        // ->join('calendar', 'calendar.id', '=', 'calendar_appointment.calendar_id')
        ->join('patient', 'patient.id', '=', 'calendar_appointment.patient_id')
        ->join('appoint_descrip', 'appoint_descrip.id', '=', 'calendar_appointment.description_id')
        ->join('clinic_location', 'clinic_location.id', '=', 'calendar_appointment.location_id')
        ->join('doctor', 'doctor.id', '=', 'calendar_appointment.doctor_id')
        ->where('calendar_appointment.id', $id)
        ->get();

        return response(['data' => $getappoint]);
    }

    public function update_appoint_sataus($id, $value){
        DB::table('calendar_appointment')
        ->where('id', '=', $id)
        ->update(['status' => $value]);

        $getappoint = DB::table('calendar_appointment')
        ->join('appoint_type', 'appoint_type.id', '=', 'calendar_appointment.appoint_type_id')
        // ->join('calendar', 'calendar.id', '=', 'calendar_appointment.calendar_id')
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
        // $calendar_id = $request->calendar_id;
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
            // 'calendar_id' => 'required|exists:calendar,id',
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

        $day = Carbon::createFromFormat('Y-m-d', $surgery_date);
        $month_name = $day->format('F');
        $year_name = $day->format('Y');

        $calendarsurgery = new CalendarSurgery;
        $calendarsurgery->appoint_type_id = $appoint_type_id;
        // $calendarsurgery->calendar_id = $calendar_id;
        $calendarsurgery->patient_id = $patient_id;
        $calendarsurgery->hospital_id = $hospital_id;
        $calendarsurgery->surgery_from = $surgery_from;
        $calendarsurgery->doctor_id = $doctor_id;
        $calendarsurgery->anesthetist = $anesthetist;
        $calendarsurgery->surgery_time = $surgery_time;
        // date type should be date. formate 2021-12-14
        $calendarsurgery->surgery_date = $surgery_date;
        $calendarsurgery->surgery_month = $month_name;
        $calendarsurgery->surgery_year = $year_name;
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

    public function update_surgery(Request $request){
        $id = $request->id;
        $appoint_type_id = $request->appoint_type_id;
        // $calendar_id = $request->calendar_id;
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
            'id' => 'required|exists:calendar_surgery,id',
            'appoint_type_id' => 'required|exists:appoint_type,id',
            // 'calendar_id' => 'required|exists:calendar,id',
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

        $day = Carbon::createFromFormat('Y-m-d', $surgery_date);
        $month_name = $day->format('F');
        $year_name = $day->format('Y');

        CalendarSurgery::where('id', $id)
        ->update(['appoint_type_id' => $appoint_type_id, 'patient_id' => $patient_id, 'hospital_id' => $hospital_id, 'surgery_from' => $surgery_from, 'doctor_id' => $doctor_id, 'anesthetist' => $anesthetist, 'surgery_time' => $surgery_time, 'surgery_date' => $surgery_date, 'surgery_month' => $month_name, 'surgery_year' =>  $year_name, 'admission_date' => $admission_date, 'surgery_note' => $surgery_note, 'surgery_temp' => $surgery_temp ]);

        SurgeryProceRela::where('surgery_id', $id)
        ->update(['procedures_id' => $procedure1_id]);
 
        SurgeryProceRela::where('surgery_id', $id)
        ->update(['procedures_id' => $procedure2_id]);

        SurgeryProceRela::where('surgery_id', $id)
        ->update(['procedures_id' => $procedure3_id]);

        return response(['success' => 'successfully updated!']);
    }

    public function get_single_surgery($id){

        $getsurgery = DB::table('calendar_surgery')
        ->join('appoint_type', 'appoint_type.id', '=', 'calendar_surgery.appoint_type_id')
        // ->join('calendar', 'calendar.id', '=', 'calendar_surgery.calendar_id')
        ->join('patient', 'patient.id', '=', 'calendar_surgery.patient_id')
        ->join('hospital', 'hospital.id', '=', 'calendar_surgery.hospital_id')
        ->where("calendar_surgery.id", $id)
        ->get();

        return response(['data' => $getsurgery]);
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
        // ->join('calendar', 'calendar.id', '=', 'calendar_surgery.calendar_id')
        ->join('patient', 'patient.id', '=', 'calendar_surgery.patient_id')
        ->join('hospital', 'hospital.id', '=', 'calendar_surgery.hospital_id')
        ->select('calendar_surgery.id', 'surgery_time', 'dname', 'surgery_note','mobile', 'hospital_name', 'notes', 'status')
        ->get();

        return response(['data' => $getsurgery]);
    }

    public function update_surgery_sataus($id, $value){
        DB::table('calendar_surgery')
        ->where('id', '=', $id)
        ->update(['status' => $value]);

        $getsurgery = DB::table('calendar_surgery')
        ->join('appoint_type', 'appoint_type.id', '=', 'calendar_surgery.appoint_type_id')
        // ->join('calendar', 'calendar.id', '=', 'calendar_surgery.calendar_id')
        ->join('patient', 'patient.id', '=', 'calendar_surgery.patient_id')
        ->join('hospital', 'hospital.id', '=', 'calendar_surgery.hospital_id')
        ->select('calendar_surgery.id', 'surgery_time', 'dname', 'surgery_note', 'mobile', 'hospital_name', 'notes', 'status')
        ->get();

        return response(['data' => $getsurgery]);
    }

    public function create_tasks(Request $request){
        $appoint_type_id = $request->appoint_type_id;
        // $calendar_id = $request->calendar_id;
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
            // 'calendar_id' => 'required|exists:calendar,id',
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

        $day = Carbon::createFromFormat('Y-m-d', $task_date);
        $month_name = $day->format('F');
        $year_name = $day->format('Y');

        $calendartasks = new CalendarTasks;
        $calendartasks->appoint_type_id = $appoint_type_id;
        // $calendartasks->calendar_id = $calendar_id;
        $calendartasks->task_time = $task_time;
        // date type should be date. formate 2021-12-14
        $calendartasks->task_date = $task_date;
        $calendartasks->task_month = $month_name;
        $calendartasks->task_year = $year_name;
        $calendartasks->doctor_id = $doctor_id;
        $calendartasks->remind = $remind;
        $calendartasks->remind_to = $remind_to;
        $calendartasks->color = $color;
        $calendartasks->task_text = $task_text;
        $calendartasks->save();

        return response(['success' => 'successfully create!']);
    }

    public function update_tasks(Request $request){
        $id = $request->id;
        $appoint_type_id = $request->appoint_type_id;
        // $calendar_id = $request->calendar_id;
        $task_time  = $request->task_time;
        // date type should be date. formate 2021-12-14
        $task_date = $request->task_date;
        $doctor_id  = $request->doctor_id;
        $remind = $request->remind;
        $remind_to  = $request->remind_to;
        $color = $request->color;
        $task_text = $request->task_text;

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:calendar_tasks,id',
            'appoint_type_id' => 'required|exists:appoint_type,id',
            // 'calendar_id' => 'required|exists:calendar,id',
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

        $day = Carbon::createFromFormat('Y-m-d', $task_date);
        $month_name = $day->format('F');
        $year_name = $day->format('Y');

        CalendarTasks::where('id', $id)
        ->update(['appoint_type_id' => $appoint_type_id, 'task_time' => $task_time, 'task_date' => $task_date, 'task_month' => $month_name, 'task_year' => $year_name, 'doctor_id' => $doctor_id, 'remind' => $remind, 'remind_to' => $remind_to, 'color' => $color, 'task_text' => $task_text
        ]);

        return response(['success' => 'successfully updated!']);
    }

    public function get_single_tasks($id){

        $gettasks = DB::table('calendar_tasks')
        ->join('appoint_type', 'appoint_type.id', '=', 'calendar_tasks.appoint_type_id')
        // ->join('calendar', 'calendar.id', '=', 'calendar_tasks.calendar_id')
        ->join('doctor', 'doctor.id', '=', 'calendar_tasks.doctor_id')
        ->where('calendar_tasks.id', $id)
        ->get();

        return response(['data' => $gettasks]);
    }

    public function get_tasks(){

        $gettasks = DB::table('calendar_tasks')
        ->join('appoint_type', 'appoint_type.id', '=', 'calendar_tasks.appoint_type_id')
        // ->join('calendar', 'calendar.id', '=', 'calendar_tasks.calendar_id')
        ->join('doctor', 'doctor.id', '=', 'calendar_tasks.doctor_id')
        ->select('calendar_tasks.id', 'task_time', 'task_text','status')
        ->get();

        return response(['data' => $gettasks]);
    }

    public function update_tasks_sataus($id, $value){
        DB::table('calendar_tasks')
        ->where('id', '=', $id)
        ->update(['status' => $value]);

        $gettasks = DB::table('calendar_tasks')
        ->join('appoint_type', 'appoint_type.id', '=', 'calendar_tasks.appoint_type_id')
        // ->join('calendar', 'calendar.id', '=', 'calendar_tasks.calendar_id')
        ->join('doctor', 'doctor.id', '=', 'calendar_tasks.doctor_id')
        ->select('calendar_tasks.id', 'task_time', 'task_text', 'status')
        ->get();

        return response(['data' => $gettasks]);
    }

    public function get_date_calendar(Request $request){
        // Date formate 2021-12-15
        $date = $request->date;

        $validator = Validator::make($request->all(), [
            'date' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $appointmentdata = DB::table('calendar_appointment')
        ->leftjoin('patient', 'calendar_appointment.patient_id', '=', 'patient.id')
        ->leftjoin('appoint_descrip', 'appoint_descrip.id', '=', 'calendar_appointment.description_id')
        ->leftjoin('clinic_location', 'clinic_location.id', '=', 'calendar_appointment.location_id')
        ->leftjoin('doctor', 'doctor.id', '=', 'calendar_appointment.doctor_id')
        ->where('appoint_date', '=', $date)
        ->select('calendar_appointment.id', 'calendar_appointment.appoint_time', 'patient.dname', 'patient.dateOfBirth', 'patient.homePhone', 'appoint_descrip.appoint_description', 'clinic_location.locatio_name', 'calendar_appointment.appoint_notes', 'calendar_appointment.status')
        ->get();

        $surgerydata = DB::table('calendar_surgery')
        ->leftjoin('patient', 'calendar_surgery.patient_id', '=', 'patient.id')
        ->leftjoin('hospital', 'hospital.id', '=', 'calendar_surgery.hospital_id')
        ->select('calendar_surgery.id', 'calendar_surgery.surgery_time', 'patient.mobile')
        ->where('surgery_date', '=', $date)
        ->get();

        foreach($surgerydata as $surgeryvalue){
            $procedureID = SurgeryProceRela::where('surgery_id', $surgeryvalue->id)->get();
            if($procedureID){
                foreach($procedureID as $key => $procedure){
                    if($key == 0){
                        $get_procedure_name = Procedures::where('id', $procedure->procedures_id)->first()->procedure_name;
                        $surgeryvalue->procedure_one = $get_procedure_name;
                    }elseif($key == 1){
                        $get_procedure_name = Procedures::where('id', $procedure->procedures_id)->first()->procedure_name;
                        $surgeryvalue->procedure_two = $get_procedure_name;
                    }elseif($key == 2){
                        $get_procedure_name = Procedures::where('id', $procedure->procedures_id)->first()->procedure_name;
                        $surgeryvalue->procedure_three = $get_procedure_name;
                    }
                }
            }
        }

        $tasksdata = DB::table('calendar_tasks')
        ->where('task_date', '=', $date)
        ->select('id','task_time', 'task_text', 'status')
        ->get();

        $calendar = DB::table('calendar')
        ->where('date', '=', $date)
        ->get();

        $alldata['appointment'] = $appointmentdata;
        $alldata['appointment'][] = "#FF6600";
        $alldata['surgery'] = $surgerydata;
        $alldata['surgery'][] = "#0065BB";
        $alldata['tasks'] = $tasksdata;
        $alldata['tasks'][] = "#66CC00";
        $alldata['calendar'] = $calendar;

        // ->select('id', 'description', 'date', 'holiday')->get();
        return response(['data' => $alldata]);
    }

    public function get_month_calendar(Request $request){
        // Date formate 2021-12-15
        $month = $request->month;
        $year = $request->year;

        $validator = Validator::make($request->all(), [
            'month' => 'required',
            'year' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $calendar = DB::table('calendar')
        ->where('calendar_month', '=', $month)
        ->where('calendar_year', '=', $year)
        ->get();

        // $appointmentdata = DB::table('calendar_appointment')
        // ->where('appoint_month', '=', $month)
        // ->where('appoint_year', '=', $year)
        // ->get();

        $appointmentdata = DB::table('calendar_appointment')
        ->leftjoin('patient', 'calendar_appointment.patient_id', '=', 'patient.id')
        ->leftjoin('appoint_descrip', 'appoint_descrip.id', '=', 'calendar_appointment.description_id')
        ->leftjoin('clinic_location', 'clinic_location.id', '=', 'calendar_appointment.location_id')
        ->leftjoin('doctor', 'doctor.id', '=', 'calendar_appointment.doctor_id')
        ->where('appoint_month', '=', $month)
        ->where('appoint_year', '=', $year)
        ->select('calendar_appointment.id', 'calendar_appointment.appoint_time', 'patient.dname', 'patient.dateOfBirth', 'patient.homePhone', 'appoint_descrip.appoint_description', 'clinic_location.locatio_name', 'calendar_appointment.appoint_notes', 'calendar_appointment.status')
        ->get();

        // $surgerydata = DB::table('calendar_surgery')
        // ->where('surgery_month', '=', $month)
        // ->where('surgery_year', '=', $year)
        // ->get();

        $surgerydata = DB::table('calendar_surgery')
        ->leftjoin('patient', 'calendar_surgery.patient_id', '=', 'patient.id')
        ->leftjoin('hospital', 'hospital.id', '=', 'calendar_surgery.hospital_id')
        ->select('calendar_surgery.id', 'calendar_surgery.surgery_time', 'patient.mobile')
        ->where('surgery_month', '=', $month)
        ->where('surgery_year', '=', $year)
        ->get();

        foreach($surgerydata as $surgeryvalue){
            $procedureID = SurgeryProceRela::where('surgery_id', $surgeryvalue->id)->get();
            if($procedureID){
                foreach($procedureID as $key => $procedure){
                    if($key == 0){
                        $get_procedure_name = Procedures::where('id', $procedure->procedures_id)->first()->procedure_name;
                        $surgeryvalue->procedure_one = $get_procedure_name;
                    }elseif($key == 1){
                        $get_procedure_name = Procedures::where('id', $procedure->procedures_id)->first()->procedure_name;
                        $surgeryvalue->procedure_two = $get_procedure_name;
                    }elseif($key == 2){
                        $get_procedure_name = Procedures::where('id', $procedure->procedures_id)->first()->procedure_name;
                        $surgeryvalue->procedure_three = $get_procedure_name;
                    }
                }
            }
        }

        // $tasksdata = DB::table('calendar_tasks')
        // ->where('task_month', '=', $month)
        // ->where('task_year', '=', $year)
        // ->get();

        $tasksdata = DB::table('calendar_tasks')
        ->where('task_month', '=', $month)
        ->where('task_year', '=', $year)
        ->select('id','task_time', 'task_text', 'status')
        ->get();

        $alldata['appointment'] = $appointmentdata;
        $alldata['appointment'][] = "#FF6600";
        $alldata['surgery'] = $surgerydata;
        $alldata['surgery'][] = "#0065BB";
        $alldata['tasks'] = $tasksdata;
        $alldata['tasks'][] = "#66CC00";
        $alldata['calendar'] = $calendar;

        return response(['data' => $alldata]);
    }
}
