<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Rate\RateService;
use App\Models\Rate;
use App\Models\Currency;
use App\Models\MMUser;
use App\Http\Requests\Rate\rate_validation;

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


    public function __construct(RateService $rateService, Currency $currency, MMUser $user, Rate $rate)
    {

        $this->currency = $currency;
        $this->user = $user;
        $this->rateService = $rateService;
        $this->rate = $rate;
    }

    public function index(Request $request)
    {
        //
        $response = $this->rateService->fetchRateList($request->all(), $this->rate, $this->currency, $this->user);

        return response()->json($response);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(rate_validation $request)
    {

        $response = $this->rateService->storeRate($request->all(), $this->rate);

        return response()->json(['rate_id' => $response]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(rate_validation $request, $rate_id)
    {


        if (!$this->rate::where('id_rate', $rate_id)->where('rate_status', 1)->exists()) {
            return response()->json(['errors' => ['rate never exists']], 422);
        }

        $response = $this->rateService->updateRate($rate_id, $request->all(), $this->rate);

        return response()->json(['rate_id' => $rate_id]);
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
        //


        if (!$this->rate::where('id_rate', $rate_id)->where('rate_status', 1)->exists()) {
            return response()->json(['errors' => ['rate never exists']], 422);
        }
        $response =  $this->rateService->deleteRate($rate_id, $this->rate);

        return response()->json([$response]);
    }


    public function todaysRate(Request $request)
    {




        $response = $this->rateService::todaysRate();

        return response()->json($response, 200);
    }
}
