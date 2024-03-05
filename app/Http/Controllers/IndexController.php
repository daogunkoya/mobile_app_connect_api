<?php

namespace App\Http\Controllers;

use _PHPStan_950705577\Fig\Http\Message\StatusCodeInterface;
use _PHPStan_950705577\React\Http\Message\Response;
use App\DTO\IndexDataObject;
use App\Models\Sender;
use App\Repositories\RateRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Repositories\SenderRepository;
use App\Repositories\TransactionRepository;
use App\Http\Resources\IndexControllerResource;

class IndexController extends Controller
{

    public function __construct(public SenderRepository      $senderRepository,
                                public RateRepository        $rateRepository,
                                public TransactionRepository $transactionRepository)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $input = $request->all();
        $type = $input['type'] ?? '';
        $senders = $type !== 'transactions' ? $this->senderRepository->fetchSenders($input) : [];
        $transactions = $type !== 'senders' ? $this->transactionRepository->fetchTransaction($input) : [];
        $exchangeRate = $this->rateRepository::todaysRate();

         return response()->json(['senders' => $senders, 'transactions' => $transactions, 'rate'=>$exchangeRate]);
        return (new IndexControllerResource(new IndexDataObject(
            $senders,
       [],
            $exchangeRate
        )))->response()->setStatusCode(200);
    }


}
