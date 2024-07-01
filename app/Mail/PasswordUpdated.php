<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordUpdated extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct( )
    {
      
    }

    public function build()
    {
        return $this->view('emails.user.password_updated')
                    ->with([]);
                    // ->attachFromStorageDisk('public', $this->imagePath);
    }
}
