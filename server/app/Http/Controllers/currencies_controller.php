<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Currency\currency_service;
use App\Models\mm_currency;
use App\Models\mm_user;
use App\Http\Requests\currency\currency_validation;

class currencies_controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,
                                    currency_service $currency_service,
                                    mm_currency $mm_currency,
                                    mm_user $mm_user)
    {
        //
        $response = $currency_service->fetch_currency_list( $request->all(),$mm_currency, $mm_currency, $mm_user);

        return response()->json( $response);
      
    }

  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(currency_validation $request,
                            currency_service $currency_service,
                             mm_currency $mm_currency,
                              )
    {

        $response = $currency_service->store_currency($request->all(), $mm_currency);

        return response()->json(['currency_id' => $response]);
       
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($currency_id, currency_validation $request,
                                currency_service $currency_service,
                            
                                mm_currency $mm_currency, 
                                mm_user $mm_user)
    {

        if(!$mm_currency::where('id_currency', $currency_id)->where('currency_status',1)->exists()) return response()->json(['errors'=>['currency never exists']], 422);

        $response = $currency_service->update_currency($currency_id, $request->all(), $mm_currency);

        return response()->json(['currency_id' => $response]);
       
    }


    public function show(Request $request, $currency_id,
                            currency_service $currency_service,
                        
                            mm_currency $mm_currency, 
                            mm_user $mm_user){

                        if(!$mm_currency::where('id_currency', $currency_id)->where('currency_status',1)->exists()) return response()->json(['errors'=>['currency never exists']], 422);
                                $response = $currency_service->fetch_currency($currency_id, $mm_currency, $mm_currency, $mm_user);

        return response()->json($response);

      }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(    $currency_id,
                                currency_service $currency_service,
                                mm_currency $mm_currency)
    {
        //
        if(!$mm_currency::where('id_currency', $currency_id)->where('currency_status',1)->exists()) return response()->json(['errors'=>['currency never exists']], 422);
        $response =  $currency_service->delete_currency($currency_id, $mm_currency);

        return response()->json([$response]);

    }
}