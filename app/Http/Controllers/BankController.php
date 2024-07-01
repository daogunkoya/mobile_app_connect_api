<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

/** @noinspection PhpInconsistentReturnPointsInspection */

namespace App\Http\Controllers;

use App\Http\Resources\BankAcceptableIdentityResource;
use App\Models\Bank;
use App\Models\AcceptableIdentity;
use App\DTO\BankAcceptableIdentityDto;
use App\Repositories\BankRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BankController extends Controller
{

    public function __construct(public BankRepository $bankRepository)
    {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Bank::get();
    }



    public function list(): JsonResponse
    {
        $seach = ['search' => request('search')] ?? request('search');

        return (new BankAcceptableIdentityResource(
            BankAcceptableIdentityDto::fromEloquentModelCollection(
                    Bank::query()->filter($seach)->get(),
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
    public function store(Request $request)
    {
        //
        foreach ($request->bank as $bank) {
            Bank::create([
                'store_id'      => store_id(),
                'name'          => $bank,
                'transfer_type' => 1,
                'status'        => 'b',

            ]);
        }

        return;
    }
}
