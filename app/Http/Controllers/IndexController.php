<?php

namespace App\Http\Controllers;

use App\Models\Sender;
use App\Repositories\RateRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Repositories\SenderRepository;
use App\Repositories\TransactionRepository;

class IndexController extends Controller
{

    public function __construct(public SenderRepository $senderRepository,
                               public RateRepository $rateRepository,
                               public TransactionRepository $transactionRepository){
    }

    public function index(Request $request): JsonResponse
    {
        $input = $request->all();
        $type = $input['type'] ?? '';

        $senders = $type !== 'transactions' ? $this->senderRepository->fetchSenders($input) : [];
        $transactions = $type !== 'senders' ? $this->transactionRepository->fetchTransaction($input) : [];
        $exchangeRate =  $this->rateRepository::todaysRate();

        return response()->json(['senders' => $senders, 'transactions' => $transactions, 'rate'=>$exchangeRate]);
    }


}
