<?php

namespace Modules\Saas\Emails;

use App\SmSchool;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user, $verifyUser;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$verifyUser)
    {
        $this->user = $user;
        $this->$verifyUser = $verifyUser;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $schools = SmSchool::find(Auth::user()->school_id);
        $school_email = @$schools->email;
        return $this->view('saas::mail.verifyUser')->from($school_email)->subject('Institution registration email verification') ;
    }
}
