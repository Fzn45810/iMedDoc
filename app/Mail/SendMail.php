<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->details['subject'];
        $attachment = $this->details['attachment'];
        $file_name = $this->details['file_name'];
        if(!is_null($attachment)){
            return $this->subject($subject)
            ->view('email.doctoremail')
            ->attach(public_path("emaiattachment/".$file_name), [
                'as' => $this->details['attachment']->getClientOriginalName()
            ]);
        }else{
            return $this->subject($subject)->view('email.doctoremail');
        }
    }
}
