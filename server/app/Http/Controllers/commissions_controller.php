<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Commission\commission_service;
use App\Models\mm_commission;
use App\Models\mm_currency;
use App\Models\mm_user;
use App\Http\Requests\Commissions\commissions_validation;

class commissions_controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,
                                    commission_service $commission_service,
                                    mm_commission $mm_commission,
                                    mm_currency $mm_currency, 
                                    mm_user $mm_user)
    {
        //
        $response = $commission_service->fetch_commission_list( $request->all(),$mm_commission, $mm_currency, $mm_user);

        return response()->json( $response);
      
    }

  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(commissions_validation $request,
                            commission_service $commission_service,
                             mm_commission $mm_commission,
                              )
    {

        $response = $commission_service->store_commission($request->all(), $mm_commission);

        return response()->json(['commission_id' => $response]);
       
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($commission_id, commissions_validation $request,
                                commission_service $commission_service,
                                mm_commission $mm_commission,
                                mm_currency $mm_currency, 
                                mm_user $mm_user)
    {

        if(!$mm_commission::where('id_commission', $commission_id)->where('commission_status',1)->exists()) return response()->json(['errors'=>['commission never exists']], 422);
        

        $response = $commission_service->update_commission($commission_id, $request->all(), $mm_commission);

        return response()->json(['commission_id' => $response]);
       
       
    }


    public function show(Request $request, $commission_id,
                            commission_service $commission_service,
                            mm_commission $mm_commission,
                            mm_currency $mm_currency, 
                            mm_user $mm_user){

                        if(!$mm_commission::where('id_commission', $commission_id)->where('commission_status',1)->exists()) return response()->json(['errors'=>['commission never exists']], 422);
                                $response = $commission_service->fetch_commission($commission_id, $mm_commission, $mm_currency, $mm_user);

        return response()->json($response);

      }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(    $commission_id,
                                commission_service $commission_service,
                                mm_commission $mm_commission)
    {
        //
        if(!$mm_commission::where('id_commission', $commission_id)->where('commission_status',1)->exists()) return response()->json(['errors'=>['commission never exists']], 422);
        $response =  $commission_service->delete_commission($commission_id, $mm_commission);

        return response()->json([$response]);

    }

    public function get_commission( Request $request,commission_service $commission_service){ 

       if(!empty($request->amount)){

          
           $amount = $request->amount;
           
           $res = $commission_service::fetch_commission_value($amount  );

           return response()->json($res);
       } 
       return response()->json(['errors'=>['amount'=>['amount is required']]]) ;   
    }
}