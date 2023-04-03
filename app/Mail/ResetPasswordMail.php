<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ResetPasswordMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $verificationCode;
    public $sendTo;
    public $clientName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user , $verificationCode)
    {
        $this->verificationCode = $verificationCode;
        $this->sendTo = $user['email'];
        $this->clientName = $user['name'];
    }



    public function build()
    {
        return $this->from('abdellah.elhilaly.96@gmail.com', 'You Code')
                    ->to($this->sendTo, $this->clientName)
                    ->subject('Reset your password')
                    ->view('Mails.ressetPassword')
                    ->with([
                        'verificationCode' => $this->verificationCode,
                        'link' => 'http://localhost:8000/verify/' . $this->verificationCode,
                    ]);
    }



    public function sendMail()
    {
        Mail::send($this);
    }


}
