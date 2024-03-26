<?php

namespace App\Actions;

use Illuminate\Support\Facades\Mail;

class SendTransactionConfirmationEmail
{


    public function handle(TransactionFulfilled $event):void
    {

        Mail::to($event->user->email)->send( new TransactionReceived($event->transaction)
        );
    }
}
