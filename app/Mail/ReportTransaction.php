<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Dto\TransactionDto;

class ReportTransaction extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct(
            public string $description,
             public $imagePath, 
             public TransactionDto $transaction
             )
    {
      
    }

    public function build()
    {
        return $this->view('emails.transactions.report')
                    ->with(['description' => $this->description, 'transaction' => $this->transaction])
                    ->attachFromStorageDisk('public', $this->imagePath);
    }
}
