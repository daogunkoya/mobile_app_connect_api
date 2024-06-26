<?php

namespace App\Http\Controllers;

use _PHPStan_950705577\Fig\Http\Message\StatusCodeInterface;
use _PHPStan_950705577\React\Http\Message\Response;
use App\DTO\IndexDataObject;
use App\Models\Sender;
use App\Repositories\RateRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\DTO\SenderDTO;
use App\DTO\TransactionDTO;
use App\DTO\CurrencyDto;
use App\DTO\HomeDto;
use App\DTO\RateDto;
use App\DTO\UserDto;
use App\Http\Resources\HomeResource;
use App\Repositories\SenderRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\CurrencyRepository;
use App\Http\Resources\IndexControllerResource;


class IndexController extends Controller
{

    public function __construct(
        public SenderRepository  $senderRepository,
        public RateRepository        $rateRepository,
        public CurrencyRepository    $currencyRepository,
        public TransactionRepository $transactionRepository)
    {
    }

    public function __invoke(Request $request)
    {
       // return 1;
        $input = $request->all();
        $user = UserDto::fromEloquentModel(auth()->user()->load('latestUserCurrency'));

        $senders = $this->senderRepository->fetchSenders($input) ;
        [ $transactionList, $totalTransaction ] = $this->transactionRepository->fetchTransaction($input, $user) ;

        $transactionDtoCollection = TransactionDto::fromEloquentModelCollection($transactionList);
        $senderDtoCollection = SenderDto::fromEloquentModelCollection($senders);
        $rate = RateDto::fromEloquentModel($this->rateRepository->fetchTodaysRate($user->userId));
        $currencyDtoCollection = CurrencyDto::fromEloquentCollection($this->currencyRepository->fetchCurrencies()->paginate(20));
        //$exchangeRate = "234";


        // return (new HomeResource(
        //     HomeDto::fromEloquentModel($transactionDtoCollection, $senderDtoCollection, $rate, $currencies)))
        // ->response()->setStatusCode(200);
        return (new HomeResource(
        compact('transactionDtoCollection','totalTransaction', 'senderDtoCollection', 'user','rate', 'currencyDtoCollection')))
        ->response()->setStatusCode(200);
    }


}
