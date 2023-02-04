<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Rate\rate_service;
use App\Models\mm_rate;
use App\Models\mm_currency;
use App\Models\mm_user;
use App\Http\Requests\Rate\rate_validation;

class rates_controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,
                                    rate_service $rate_service,
                                    mm_rate $mm_rate,
                                    mm_currency $mm_currency, 
                                    mm_user $mm_user)
    {
        //
        $response = $rate_service->fetch_rate_list( $request->all(),$mm_rate, $mm_currency, $mm_user);

        return response()->json( $response);
      
    }

  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(rate_validation $request,
                            rate_service $rate_service,
                             mm_rate $mm_rate,
                              )
    {

        $response = $rate_service->store_rate($request->all(), $mm_rate);

        return response()->json(['rate_id' => $response]);
       
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($rate_id, rate_validation $request,
                                rate_service $rate_service,
                                mm_rate $mm_rate,
                                mm_currency $mm_currency, 
                                mm_user $mm_user)
    {

        if(!$mm_rate::where('id_rate', $rate_id)->where('rate_status',1)->exists()) return response()->json(['errors'=>['rate never exists']], 422);

        $response = $rate_service->update_rate($rate_id, $request->all(), $mm_rate);

        return response()->json(['rate_id' => $response]);
       
    }


    public function show(Request $request, $rate_id,
                            rate_service $rate_service,
                            mm_rate $mm_rate,
                            mm_currency $mm_currency, 
                            mm_user $mm_user){

                        if(!$mm_rate::where('id_rate', $rate_id)->where('rate_status',1)->exists()) return response()->json(['errors'=>['rate never exists']], 422);
                                $response = $rate_service->fetch_rate($rate_id, $mm_rate, $mm_currency, $mm_user);

        return response()->json($response);

      }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(    $rate_id,
                                rate_service $rate_service,
                                mm_rate $mm_rate)
    {
        //
        if(!$mm_rate::where('id_rate', $rate_id)->where('rate_status',1)->exists()) return response()->json(['errors'=>['rate never exists']], 422);
        $response =  $rate_service->delete_rate($rate_id, $mm_rate);

        return response()->json([$response]);

    }


    public function todays_rate(Request $request, rate_service $rate_service, mm_rate $mm_rate){
      
        $response = $rate_service::todays_rate();
        
        return response()->json($response, 200);

    }
}