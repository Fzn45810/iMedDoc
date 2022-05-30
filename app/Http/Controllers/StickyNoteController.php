<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StickyNote;
use Illuminate\Support\Facades\Validator;
use DB;

class StickyNoteController extends Controller
{
    public function create_stickynote(Request $request){
        $belongsto = $request->belongsto;
        $notes_description = $request->notes_description;

        if(trim($belongsto) == 'doctor' || trim($belongsto) == 'secretary' || trim($belongsto) == 'reminder'){

            $validator = Validator::make($request->all(), [
                'belongsto' => 'required',
                'notes_description' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }

            $stickynote = new StickyNote;
            $stickynote->belongsto = $belongsto;
            $stickynote->notes_description = $notes_description;
            $stickynote->save();

            return response(['success' => 'successfully create!']);
        }else{
            return response(['error' => 'belongsto type should be doctor or secretary or reminder!']);
        }
    }

    public function update_stickynote_des(Request $request){
        $id = $request->id;
        $notes_description = $request->notes_description;

        $validator = Validator::make($request->all(), [
            'notes_description' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $stickyNote = StickyNote::where('id', $id)->first();

        if($stickyNote){
            StickyNote::where('id', $id)
            ->update(['notes_description' => $notes_description]);

            return response(['success' => 'successfully update!']);
        }else{
            return response(['message' => 'dictation not exist!']);
        }
    }

    public function update_stickynote_is_active(Request $request){
        $id = $request->id;
        $is_active = $request->is_active;

        $validator = Validator::make($request->all(), [
            'is_active' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $stickyNote = StickyNote::where('id', $id)->first();

        if($stickyNote){
            StickyNote::where('id', $id)
            ->update(['is_active' => $is_active]);

            return response(['success' => 'successfully update!']);
        }else{
            return response(['message' => 'dictation not exist!']);
        }
    }

    public function get_sticky_notes(){
        $get_doctor = DB::table('sticky_notes')
        ->select('sticky_notes.id', 'is_active','belongsto', 'notes_description')
        ->where('belongsto', 'doctor')
        ->get();

        $get_secretary = DB::table('sticky_notes')
        ->select('sticky_notes.id','is_active' ,'belongsto', 'notes_description')
        ->where('belongsto', 'secretary')
        ->get();

        $get_reminder = DB::table('sticky_notes')
        ->select('sticky_notes.id','is_active' ,'belongsto', 'notes_description')
        ->where('belongsto', 'reminder')
        ->get();

        $get_data = ['doctor' => $get_doctor, 'secretary' => $get_secretary, 'reminder' => $get_reminder];

        return response(['data' => $get_data]);
    }

    public function get_single_sticky_note($id){
        $get_data = DB::table('sticky_notes')
        ->select('sticky_notes.id', 'is_active', 'belongsto', 'notes_description')
        ->where('id', $id)
        ->get();

        return response(['data' => $get_data]);
    }
}
