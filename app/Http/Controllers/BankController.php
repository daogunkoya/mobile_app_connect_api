<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

/** @noinspection PhpInconsistentReturnPointsInspection */

namespace App\Http\Controllers;

use App\Http\Resources\BankAcceptableIdentityResource;
use App\Http\Resources\BankResource;
use App\Models\Bank;
use App\Models\AcceptableIdentity;
use App\DTO\BankAcceptableIdentityDto;
use App\DTO\BankDto;
use App\Repositories\BankRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Filters\BankFilter;
use App\Http\Requests\Bank\BankRequest;
use App\Http\Requests\Bank\BankVerifyRequest;
use App\Interfaces\Bank\BanksSyncInterface;
use App\Actions\Bank\VerifyBankAction;
use App\http\Resources\BankAccountNumberResource;

class BankController extends Controller
{

    public function __construct(public BankRepository $bankRepository, public BankFilter $bankFilter)
    {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      //  return response()->json($this->bankRepository->fetchBanks($request->all()));
        // return BankDto::fromEloquentCollection(
        //     $this->bankRepository->fetchBanks($request->all()) );
       
       return ( BankResource::collection(
        BankDto::fromEloquentCollection(
            $this->bankRepository->fetchBanks($request->all())
       )
        
    ))->response()->setStatusCode(Response::HTTP_OK);

    }



    public function list(): JsonResponse
    {
        $seach = ['search' => request('search')] ?? request('search');

        return (new BankAcceptableIdentityResource(
            BankAcceptableIdentityDto::fromEloquentModelCollection(
                    Bank::query()->filter($this->bankFilter)->get(),
                    AcceptableIdentity::query()->filter($seach)->get()
                )
        ))->response()->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return void
     */
    // public function store(Request $request)
    // {
    //     //
    //     foreach ($request->bank as $bank) {
    //         Bank::create([
    //             'store_id'      => store_id(),
    //             'name'          => $bank,
    //             'transfer_type' => 1,
    //             'status'        => 'b',

    //         ]);
    //     }

    //     return;
    // }

    public function store(BankRequest $request): JsonResponse
    {

        $bank = Bank::create($request->validated());

        return (new BankResource(
            (BankDto::fromEloquentModel( $bank))
        ))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(BankRequest $request, Bank $bank): JsonResponse
    {
        return $bank->update($request->all()) ?
            (new BankResource(BankDto::fromEloquentModel( $bank->fresh())))->response()->setStatusCode(Response::HTTP_OK)
            : response()->json(["error" => 'Something went wrong'], 400);

    }

  

    public function destroy(Request $request,Bank $bank): JsonResponse
    {
       $update =  $bank->update(['bank_status' => '0']);
        return response()->json([], 204);
    }

    public function syncBanks(BanksSyncInterface $bankSyncService)
    {
        $bankSyncService->syncBankData();

        return response()->json(['message' => 'Bank data synchronized successfully']);
    }

    public function fetchAccountdetails(BankVerifyRequest $request, VerifyBankAction $verifyBankAction)
    {
        $input = $request->validated();
        $bankCode = Bank::where('id', $input['bank_id'])->value('bank_code');

        $bankAccountDetails = $verifyBankAction->handle($bankCode,$input['account_number']);

        return (new BankAccountNumberResource($bankAccountDetails))->response()->setStatusCode(Response::HTTP_OK);

    }
}
