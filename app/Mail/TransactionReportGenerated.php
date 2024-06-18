<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionReportGenerated extends Mailable
{
    use Queueable, SerializesModels;

    // protected $filePath;

    public function __construct(
        protected string  $filePath,
        protected string $format)
    {
        // $this->filePath = $filePath;
   
        // $this->format   = $format;
    }

    public function build()
    {
        return $this->view('emails.report.generated')
                    ->attach($this->filePath, [
                        'as' => basename($this->filePath),
                        'mime' => $this->format === 'pdf' ? 'application/pdf' : 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ]);
    }
}
