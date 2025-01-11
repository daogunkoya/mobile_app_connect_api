<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerificationMail extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct(
        public string $verificationToken,
        public $user
     )
    {
      
    }

    public function build()
    {
        return $this->view('emails.user.email_verify')
        ->with(['token' => $this->verificationToken, 'user' => $this->user, 'url_link' => url('/v1/user/verify-email?token=' . $this->verificationToken.'&email='. $this->user->email)]);
                    // ->attachFromStorageDisk('public', $this->imagePath);
    }
}
