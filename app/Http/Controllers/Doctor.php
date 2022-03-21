<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class Doctor extends Controller
{
    public function get_doctor(){
        $get_all = DB::table('users')
        ->join('doctor', 'users.id', 'doctor.user_id')
        ->select('doctor.id', 'fname', 'email')
        ->get();
        return response(['data' => $get_all]);
    }
}
