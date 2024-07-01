<?php

namespace App\Jobs;

use App\Mail\TransactionReportGenerated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Exports\ExportClass;
use App\Repositories\TransactionRepository;
use App\Collections\TransactionCollection;
use App\DTO\UserDto;
use App\DTO\TransactionDto;
use App\Models\Transaction;

class GenerateTransactionReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // protected $email;
    // protected $format;
    public $tries = 100; // Adjust this number as needed

    public function __construct(
        protected UserDto $user,
        protected string $format,
        protected string $startDate,
        protected string $endDate
    ) {
        // $this->email = $email;
        // $this->format = $format;
    }

    public function handle()
    {
        $data =  TransactionDto::fromEloquentModelCollection(
            TransactionRepository::fetchUserTransactions(
                 ['date' =>['from' => $this->startDate, 'to' => $this->endDate, 'userId' => $this->user->userId]])
        );

        $filePath = storage_path('app/public/report.' . $this->format);

        //  var_dump( TransactionDto::toArrayCollection($data));
        if ($this->format === 'pdf') {
            //  $pdf = PDF::loadView('reports.pdf', TransactionDto::toArrayCollection($data));
            $pdf = PDF::loadView('reports.pdf', ['transactions' => TransactionDto::toArrayCollection($data)]);
            $pdf->save($filePath);
        } else if ($this->format === 'excel') {
            Excel::store(new ExportClass($data), 'public/report.xlsx');
            $filePath = storage_path('app/public/report.xlsx');
        }

        // var_dump($filePath);

        Mail::to($this->user->email)->send(new TransactionReportGenerated($filePath, $this->format));
    }
}
