<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\AllEmail;
use App\Models\EmailSent;
use App\Models\EmailArchive;
use App\Models\EmailDrafts;
use Illuminate\Support\Facades\Validator;
use DB;
use Mailbox;

class MailController extends Controller
{
    public function SendEmail(Request $request){

        $to = $request->to;
        $subject  = $request->subject;
        $email_text = $request->email_text;
        $cc = $request->cc;
        $bcc = $request->bcc;

        $validator = Validator::make($request->all(), [
            'to' => 'required|email',
            'email_text' => 'required',
            'cc' => 'email',
            'bcc' => 'email'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $details = [
            'subject' => $subject,
            'body' => $email_text,
        ];

        Mail::to($to)->send(new \App\Mail\SendMail($details));

        $allemail = new AllEmail;
        $allemail->to = $to;
        $allemail->from = 'muhamadi8is2@gmail.com';
        $allemail->subject = $subject;
        $allemail->email_text = $email_text;
        $allemail->cc = $cc;
        $allemail->bcc = $bcc;
        $allemail->save();

        $sendemail = new EmailSent;
        $sendemail->email_id = $allemail->id;
        $sendemail->save();

        return response(['success' => 'email successfully sent!']);

        // Mailbox::from('m.faizankhan4510@gmail.com', function (InboundEmail $email) {
        //     $subject = $email->subject();
        // dd($subject);
        // });
    }

    public function GetAllSendEmail(){
        $getsentmail = DB::table('all_email')
        ->join('email_sent', 'email_sent.email_id', 'all_email.id')
        ->select('all_email.id', 'to','to_name', 'from','from_name' ,'subject', 'email_text', 'cc', 'bcc')->get();
        return response(['data' => $getsentmail]);
    }

    public function ArchivEmail(Request $request){
        $all_email_id = $request->email_id;

        $validator = Validator::make($request->all(), [
            'email_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $getsentemail = EmailSent::where('email_id', $all_email_id)->first();
        if($getsentemail){
            $archive_email = new EmailArchive;
            $archive_email->email_id = $all_email_id;
            $archive_email->save();

            EmailSent::where('email_id', $all_email_id)->delete();

            return response(['success' => 'email successfully archive!']);
        }else{
            return response(['message' => 'email id not found!']);
        }
    }

    public function GetAllArchiveEmail(){
        $get_archive_email = DB::table('all_email')
        ->join('email_archive', 'email_archive.email_id', 'all_email.id')
        ->select('all_email.id', 'to','to_name', 'from','from_name' ,'subject', 'email_text', 'cc', 'bcc')->get();
        return response(['data' => $get_archive_email]);
    }

    public function DraftEmail(Request $request){

        $to = $request->to;
        $subject  = $request->subject;
        $email_text = $request->email_text;
        $cc = $request->cc;
        $bcc = $request->bcc;

        // $validator = Validator::make($request->all(), [
        //     'to' => 'email',
        //     'cc' => 'email',
        //     'bcc' => 'email'
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['error'=>$validator->errors()], 401);
        // }

        $allemail = new AllEmail;
        $allemail->to = $to;
        $allemail->from = 'muhamadi8is2@gmail.com';
        $allemail->subject = $subject;
        $allemail->email_text = $email_text;
        $allemail->cc = $cc;
        $allemail->bcc = $bcc;
        $allemail->save();

        $draftemail = new EmailDrafts;
        $draftemail->email_id = $allemail->id;
        $draftemail->save();

        return response(['success' => 'email succesfylly drafted!']);
    }

    public function SentDraftEmail(Request $request){
        $all_email_id = $request->email_id;

        $to = $request->to;
        $subject  = $request->subject;
        $email_text = $request->email_text;
        $cc = $request->cc;
        $bcc = $request->bcc;

        $validator = Validator::make($request->all(), [
            'to' => 'required|email',
            'email_text' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $details = [
            'subject' => $subject,
            'body' => $email_text,
        ];

        Mail::to($to)->send(new \App\Mail\SendMail($details));

        AllEmail::where('id', $all_email_id)->update(['to' => $to, 'subject' => $subject, 'email_text' => $email_text, 'cc' => $cc, 'bcc' => $bcc]);

        $sendemail = new EmailSent;
        $sendemail->email_id = $all_email_id;
        $sendemail->save();

        EmailDrafts::where('email_id', $all_email_id)->delete();

        return response(['success' => 'email successfully send!']);
    }

    public function GetDraftEmail(){
        $get_draft_email = DB::table('all_email')
        ->join('email_drafts', 'email_drafts.email_id', 'all_email.id')
        ->select('all_email.id', 'to','to_name', 'from','from_name' ,'subject', 'email_text', 'cc', 'bcc')->get();
        return response(['data' => $get_draft_email]);
    }
}
