<?php

namespace App\Http\Controllers;

use App\DTO\RateDto;
use App\DTO\UserDto;
use App\Repositories\RateRepository;
use Illuminate\Http\Request;
use App\Services\Rate\RateService;
use App\Models\Rate;
use App\Models\Currency;
use App\Models\User;
use App\Http\Requests\Rate\RateValidation;
use App\Http\Resources\RateResource;
use Symfony\Component\HttpFoundation\Response;

class RateController extends Controller
{
    public $rateService;
    public $currency;
    public $user;
    public $rate;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function __construct(public RateRepository $rateRepository,
        RateService $rateService, Currency $currency, User $user, Rate $rate)
    {

        $this->currency = $currency;
        $this->user = $user;
        $this->rateService = $rateService;
        $this->rate = $rate;
    }

    public function index(Request $request)
    {
        //
       // $fetchRated = $this->rateRepository->fetchRate($request->all(), UserDto::fromEloquentModel(auth()->user()));
       $fetchRated = $this->rateRepository->fetchRate($request, UserDto::fromEloquentModel(auth()->user()));
        
        return  RateResource::collection(RateDto::fromEloquentModelCollection($fetchRated))
        ->response()->setStatusCode(Response::HTTP_OK);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RateValidation $request)
    {

        $rate = auth()->user()->rate()->create($request->validated());
        return (new RateResource(
            (RateDto::fromEloquentModel( $rate))
        ))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(Rate $rate, RateValidation $request)
    {
        return $rate->update($request->validated()) ?
            response()->json([], Response::HTTP_OK)
            : response()->json(["error" => 'Something went wrong'], 400);

    }


   
    public function show(Request $request, $rate_id)
    {



        if (!$this->rate::where('id_rate', $rate_id)->where('rate_status', 1)->exists()) {
            return response()->json(['errors' => ['rate never exists']], 422);
        }
                        $response = $this->rateService->fetchRate($rate_id, $this->rate, $this->currency, $this->user);

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($rate_id)
    {
        
        if (!$this->rate::where('id_rate', $rate_id)->where('rate_status', 1)->exists()) {
            return response()->json(['errors' => ['rate never exists']], 422);
        }
        $response =  $this->rateRepository->deleteRate($rate_id);

        return response()->json([$response]);
    }


    public function todaysRate(Request $request)
    {




        $response = $this->rateRepository::todaysRate();

        return response()->json($response, 200);
    }
}
