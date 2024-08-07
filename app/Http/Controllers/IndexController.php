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
use App\DTO\ReceiverDTO;
use App\DTO\TransactionDTO;
use App\DTO\CurrencyDto;
use App\DTO\HomeDto;
use App\DTO\RateDto;
use App\DTO\UserDto;
use App\Http\Resources\HomeResource;
use App\Repositories\SenderRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\CurrencyRepository;
use App\Repositories\UserRepository;
use App\Repositories\ReceiverRepository;
use App\Http\Resources\IndexControllerResource;
use App\Enum\UserRoleType;
use App\Models\Receiver;

class IndexController extends Controller
{

    public function __construct(
        protected SenderRepository  $senderRepository,
        protected ReceiverRepository $receiverRepository,
        protected RateRepository        $rateRepository,
        protected CurrencyRepository    $currencyRepository,
        protected TransactionRepository $transactionRepository,
        protected UserRepository        $userRepository
        )
    {
    }

    public function __invoke(Request $request)
    {
       // return 1;
        $input = $request->all();
        $user = UserDto::fromEloquentModel(auth()->user()->load('latestUserCurrency'));

        $userDtoCollection = [];
        if($user->userRoleType == UserRoleType::ADMIN) {
          [ $users, $totalUsers]= $this->userRepository->fetchUsers($input, $user);
            $userDtoCollection = UserDto::fromEloquentModelCollection($users);

        } 

        $senders = $user->userRoleType == UserRoleType::AGENT? $this->senderRepository->fetchSenders($input) : []; 
        $receivers = $user->userRoleType == UserRoleType::CUSTOMER? $this->receiverRepository->fetchReceiver($user->userId) : []; 
        [ $transactionList, $totalTransaction ] = $this->transactionRepository->fetchTransaction($input, $user) ;

        $transactionDtoCollection = TransactionDto::fromEloquentModelCollection($transactionList);
        $senderDtoCollection = $user->userRoleType == UserRoleType::AGENT? SenderDto::fromEloquentModelCollection($senders): [];
        $receiverDtoCollection = $user->userRoleType == UserRoleType::CUSTOMER? ReceiverDto::fromEloquentModelCollection($receivers): [];
        $rate = RateDto::fromEloquentModel($this->rateRepository->fetchTodaysRate($user->userId));
        $currencyDtoCollection = CurrencyDto::fromEloquentCollection($this->currencyRepository->fetchCurrencies()->paginate(20));
        //$exchangeRate = "234";


        // return (new HomeResource(
        //     HomeDto::fromEloquentModel($transactionDtoCollection, $senderDtoCollection, $rate, $currencies)))
        // ->response()->setStatusCode(200);
        return (new HomeResource(
        compact(
            'transactionDtoCollection',
            'totalTransaction', 
            'senderDtoCollection', 
            'receiverDtoCollection',
            'user',
            'userDtoCollection',
            'rate', 
            'currencyDtoCollection'
            )))
        ->response()->setStatusCode(200);
    }


}
