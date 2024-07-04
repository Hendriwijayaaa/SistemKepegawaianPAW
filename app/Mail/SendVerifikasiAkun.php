<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendVerifikasiAkun extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $userEmail;
    private $token;
    public function __construct($userEmail, $token)
    {
       $this->userEmail = $userEmail;
       $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
   public function build()
    {
        return $this->subject("Kwarda Sumsel Center 🔧")
        ->view('pages.mail.verifikasiakunpendaftaran')
        ->with(['email' => $this->userEmail, 'token' => $this->token]);
    }
}
