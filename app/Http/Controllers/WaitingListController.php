<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WaitingList;
use App\Models\PatientModel;
use Illuminate\Support\Facades\Validator;
use DB;

class WaitingListController extends Controller
{

    public function create(Request $request){
        $patient_id = $request->patient_id;
        // waitingFrom type should be date. formate 2021-12-14
        $waitingFrom  = $request->waitingFrom;
        // This should be appoint_type id
        $waitingFor = $request->waitingFor;
        // procedure_id or Appt._id one of it is required
        $procedures_id = $request->procedures_id;
        $appoint_id = $request->appoint_id;
        $priority = $request->priority;
        $notes = $request->notes;

        $validator = Validator::make($request->all(), [
            'patient_id' => 'required',
            'waitingFrom' => 'required',
            'waitingFor' => 'required|exists:appoint_type,id',
            'priority' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $patient = PatientModel::where('id', $patient_id)->first();
        $inwiating = WaitingList::where('patient_id', $patient_id)->first();

        if($patient){
            if(!$inwiating){
                $wiatinglist = new WaitingList;
                $wiatinglist->patient_id = $patient_id;
                $wiatinglist->waitingFrom = $waitingFrom;
                $wiatinglist->waitingFor = $waitingFor;
                $wiatinglist->procedures_id = $procedures_id;
                $wiatinglist->appoint_id = $appoint_id;
                $wiatinglist->priority = $priority;
                $wiatinglist->notes = $notes;
                $wiatinglist->save();

                return response(['success' => 'successfully added!']);
            }else{
                return response(['message' => 'patient already in waiting list!']);
            }
        }else{
            return response(['message' => 'patient not exist!']);
        }
    }

    public function update(Request $request){
        $patient_id = $request->patient_id;
        // waitingFrom type should be date. formate 2021-12-14
        $waitingFrom  = $request->waitingFrom;
        // This should be appoint_type id
        $waitingFor = $request->waitingFor;
        // procedure_id or Appt._id one of it is required
        $procedures_id = $request->procedures_id;
        $appoint_id = $request->appoint_id;
        $priority = $request->priority;
        $notes = $request->notes;

        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patient,id',
            'waitingFrom' => 'required',
            'waitingFor' => 'required|exists:appoint_type,id',
            'priority' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $patient = PatientModel::where('id', $patient_id)->first();
        $inwiating = WaitingList::where('patient_id', $patient_id)->first();

        if($patient){
            if($inwiating){
                $updatewiating = WaitingList::where('patient_id', $patient_id)
                ->update(['waitingFrom' => $waitingFrom, 'waitingFor' => $waitingFor, 'procedures_id' => $procedures_id, 'appoint_id' => $appoint_id, 'priority' => $priority, 'notes' => $notes]);

                if($updatewiating){
                    return response(['success' => 'successfully updated!']);
                }else{
                    return response(['message' => 'please try again!']);
                }
            }else{
                return response(['message' => 'patient not found!']);
            }
        }else{
            return response(['message' => 'patient not exist!']);
        }
    }

    public function GetWaitinglist(){
        $list_surgery = DB::table('waiting_list')
        ->join('patient', 'patient.id', 'waiting_list.patient_id')
        ->join('users', 'users.id', 'patient.user_id')
        ->join('procedures', 'procedures.id', 'waiting_list.procedures_id')
        ->join('appoint_type', 'appoint_type.id', 'waiting_list.waitingFor')
        ->join('title_table', 'title_table.id', 'patient.title_type_id')
        ->select('fname', 'surname', 'title_name', 'waitingFrom', 'procedures.procedure_name', 'priority', 'appoint_type.appoint_name', 'waiting_list.id')
        ->get();

        $list_appointment = DB::table('waiting_list')
        ->join('patient', 'patient.id', 'waiting_list.patient_id')
        ->join('users', 'users.id', 'patient.user_id')
        ->join('appoint_descrip', 'appoint_descrip.id', 'waiting_list.appoint_id')
        ->join('appoint_type', 'appoint_type.id', 'waiting_list.waitingFor')
        ->join('title_table', 'title_table.id', 'patient.title_type_id')
        ->select('fname', 'surname', 'title_name', 'waitingFrom', 'appoint_descrip.appoint_description', 'priority', 'appoint_type.appoint_name', 'waiting_list.id')
        ->get();

        $alldata = [$list_surgery, $list_appointment];

        return response(['data' => $alldata]);
    }
}
