<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Messages;
use App\Models\Role;

class ChatController extends Controller
{
    public function ChatMessage(Request $request){
        $message = $request->message;
        $senderid = $request->senderId;
        $recieverid = $request->recieverId;

        $get_all = DB::table('users')
        ->join('role_user', 'users.id', '=', 'role_user.user_id')
        ->join('roles', 'roles.id', '=', 'role_user.role_id')
        ->where('user_id', $senderid)
        ->first();

        $get_role = Role::where('id', $get_all->role_id)->first();
        $chatmessage = new Messages;
        if($get_role->name == 'patient'){
            $chatmessage->doctor_id = $recieverid;
            $chatmessage->patient_id = $senderid;
        }elseif($get_role->name == 'doctor'){
            $chatmessage->doctor_id = $senderid;
            $chatmessage->patient_id = $recieverid;
        }
        $chatmessage->sender_receiver = $get_all->role_id;
        $chatmessage->isread = 1;
        $chatmessage->message = $message;
        $chatmessage->save();

        return response(['success' => 'message successfully sent!']);
    }
}
