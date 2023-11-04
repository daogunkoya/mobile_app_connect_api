<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

/** @noinspection PhpInconsistentReturnPointsInspection */

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Repositories\BankRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BankController extends Controller
{

    public function __construct(public BankRepository $bankRepository){

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



    public function list():JsonResponse
    {

        return response()->json($this->bankRepository->fetchBankIdList());
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
