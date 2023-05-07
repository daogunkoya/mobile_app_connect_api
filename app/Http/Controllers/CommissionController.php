<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Commission\CommissionService;
use App\Models\Commission;
use App\Models\Currency;
use App\Models\MMUser;
use App\Http\Requests\Commissions\commissions_validation;

class CommissionController extends Controller
{

    public $comissionService;
    public $commission;
    public $currency;
    public $user;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function __construct(CommissionService $comissionService, Commission $commission, Currency $currency, MMUser $user){

                    $this->comissionService = $comissionService;
                    $this->commission = $commission;
                    $this->user = $user;
                    $this->currency = $currency;
    }
    public function index(Request $request)
    {
        //
        $response = $this->comissionService->fetchCommissionList( $request->all(),$this->commission, $this->currency, $this->user);

        return response()->json( $response);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(commissions_validation $request
                              )
    {

        $response = $this->comissionService->storeCommission($request->all(), $this->commission);

        return response()->json(['commission_id' => $response]);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(commissions_validation $request,$commission_id)
    {



        if(!$this->commission::where('id_commission', $commission_id)->where('commission_status',1)->exists()) return response()->json(['errors'=>['commission never exists']], 422);


        $response = $this->comissionService->updateCommission($commission_id, $request->all(), $this->commission);

        return response()->json(['commission_id' => $commission_id]);


    }


    public function show(Request $request, $commission_id, CommissionService $commission_service){


            if(!$this->commission::where('id_commission', $commission_id)->where('commission_status',1)->exists()) return response()->json(['errors'=>['commission never exists']], 422);
                $response = $commission_service->fetchCommission($commission_id, $this->commission, $this->currency, $this->user);

        return response()->json($response);

      }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(    $commission_id)
    {

        $commission_service   = new CommissionService;
        $commission = new Commission;
        //
        if(!$commission::where('id_commission', $commission_id)->where('commission_status',1)->exists()) return response()->json(['errors'=>['commission never exists']], 422);
        $response =  $commission_service->deleteCommission($commission_id, $commission);

        return response()->json([$response]);

    }

    public function getCommission(Request $request){



       if(!empty($request->amount)){


           $amount = $request->amount;

           $res = $this->commissionService::fetch_commission_value($amount);

           return response()->json($res);
       }
       return response()->json(['errors'=>['amount'=>['amount is required']]]) ;
    }
}
