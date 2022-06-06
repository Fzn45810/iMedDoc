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
        $attachment = $request->file('attachment');

        $validator = Validator::make($request->all(), [
            'to' => 'required|email',
            'email_text' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        if(!is_null($attachment)){
            $extention = $attachment->getClientOriginalExtension();

            $file_name = rand(11111111, 99999999).'.'.$extention;
            $attachment->move(public_path("emaiattachment/"), $file_name);
        }else{
            $file_name = '';
        }

        $details = [
            'subject' => $subject,
            'body' => $email_text,
            'attachment' => $attachment,
            'file_name' => $file_name
        ];

        Mail::to($to)->send(new \App\Mail\SendMail($details));

        $allemail = new AllEmail;
        $allemail->to = $to;
        $allemail->from = 'muhamadi8is2@gmail.com';
        $allemail->subject = $subject;
        $allemail->email_text = $email_text;
        $allemail->attachment= $file_name;
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

    public function get_all_send_email(){
        $app_name = 'https://demoimed.nextbitsolution.com/emaiattachment/';
        $getsentmail = DB::table('all_email')
        ->join('email_sent', 'email_sent.email_id', 'all_email.id')
        ->select('all_email.id', 'to','to_name', 'from','from_name' ,'subject', 'email_text', 'cc', 'bcc', 'all_email.attachment')->get();
        return response(['data' => $getsentmail]);
    }

    public function archiv_email(Request $request){
        $email_id = $request->email_id;

        $validator = Validator::make($request->all(), [
            'email_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $getsentemail = EmailSent::where('email_id', $email_id)->first();
        if($getsentemail){
            $archive_email = new EmailArchive;
            $archive_email->email_id = $email_id;
            $archive_email->save();

            EmailSent::where('email_id', $email_id)->delete();

            return response(['success' => 'email successfully archive!']);
        }else{
            return response(['message' => 'email id not found!']);
        }
    }

    public function get_all_archive_email(){
        $app_name = 'https://demoimed.nextbitsolution.com/emaiattachment/';
        $get_archive_email = DB::table('all_email')
        ->join('email_archive', 'email_archive.email_id', 'all_email.id')
        ->select('all_email.id', 'to','to_name', 'from','from_name' ,'subject', 'email_text', 'cc', 'bcc', 'all_email.attachment')->get();
        return response(['data' => $get_archive_email]);
    }

    public function DraftEmail(Request $request){
        $to = $request->to;
        $subject  = $request->subject;
        $email_text = $request->email_text;
        $cc = $request->cc;
        $bcc = $request->bcc;
        $attachment = $request->file('attachment');

        if(!is_null($to)){
            $validator = Validator::make($request->all(), [
                'to' => 'email'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }
        }

        if(!is_null($attachment)){
            $extention = $attachment->getClientOriginalExtension();
            $file_name = rand(11111111, 99999999).'.'.$extention;
            $attachment->move(public_path("emaiattachment/"), $file_name);
        }else{
            $file_name = '';
        }

        $allemail = new AllEmail;
        $allemail->to = $to;
        $allemail->from = 'muhamadi8is2@gmail.com';
        $allemail->subject = $subject;
        $allemail->email_text = $email_text;
        $allemail->attachment = $file_name;
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

        $get_draft = AllEmail::where('id', $all_email_id)->first();

        $to = $request->to;
        $subject  = $request->subject;
        $email_text = $request->email_text;
        $cc = $request->cc;
        $bcc = $request->bcc;
        $attachment = $request->file('attachment');

        $validator = Validator::make($request->all(), [
            'to' => 'required|email',
            'email_text' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        if(is_null($get_draft)){
            return response(['error' => 'draft email not found!']);
        }

        if(!is_null($attachment)){
            $extention = $attachment->getClientOriginalExtension();
            $file_name = rand(11111111, 99999999).'.'.$extention;
            $attachment->move(public_path("emaiattachment/"), $file_name);
        }else{
            $file_name = '';
        }

        $details = [
            'subject' => $subject,
            'body' => $email_text,
            'attachment' => $attachment,
            'file_name' => $file_name
        ];

        Mail::to($to)->send(new \App\Mail\SendMail($details));

        AllEmail::where('id', $all_email_id)->update(['to' => $to, 'subject' => $subject, 'email_text' => $email_text, 'cc' => $cc, 'bcc' => $bcc, 'attachment' => $file_name]);

        $sendemail = new EmailSent;
        $sendemail->email_id = $all_email_id;
        $sendemail->save();

        EmailDrafts::where('email_id', $all_email_id)->delete();

        return response(['success' => 'email successfully sent!']);
    }

    public function GetDraftEmail(){
        $app_name = 'https://demoimed.nextbitsolution.com/emaiattachment/';
        $get_draft_email = DB::table('all_email')
        ->join('email_drafts', 'email_drafts.email_id', 'all_email.id')
        ->select('all_email.id', 'to','to_name', 'from','from_name' ,'subject', 'email_text', 'cc', 'bcc', 'all_email.attachment')->get();
        return response(['data' => $get_draft_email]);
    }
}
