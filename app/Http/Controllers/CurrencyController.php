<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Currency\CurrencyService;
use App\Models\Currency;
use App\Models\MMUser;
use App\Http\Requests\currency\currency_validation;

class CurrencyController extends Controller
{

    public $currencyService;
    public $mmCurrency;
    public $user;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


     public function __construct(CurrencyService $currencyService, Currency $mmCurrency, MMUser $user)
     {
         $this->currencyService = $currencyService;
         $this->mmCurrency = $mmCurrency;
         $this->user = $user;
     }

    public function index(Request $request)
    {
        //
        $response = $this->currencyService->fetchCurrencyList( $request->all(),$this->mmCurrency, $this->mmCurrency, $this->user);

        return response()->json( $response);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(currency_validation $request )
    {


        $response = $this->currencyService->storeCurrency($request->all(), $this->mmCurrency);

        return response()->json(['currency_id' => $response]);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($currency_id, currency_validation $request )
    {

        if(!$this->mmCurrency::where('id_currency', $currency_id)->where('currency_status',1)->exists()) return response()->json(['errors'=>['currency never exists']], 422);

        $response = $this->currencyService->updateCurrency($currency_id, $request->all(), $this->mmCurrency);

        return response()->json(['currency_id' => $currency_id]);

    }


    public function show(Request $request, $currency_id, CurrencyService $currency_service){

                        if(!$this->mmCurrency::where('id_currency', $currency_id)->where('currency_status',1)->exists()) return response()->json(['errors'=>['currency never exists']], 422);
                                $response = $currency_service->fetchCurrency($currency_id, $this->mmCurrency, $this->mmCurrency, $this->user);

        return response()->json($response);

      }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(    $currency_id)
    {
        //
        if(!$this->mmCurrency::where('id_currency', $currency_id)->where('currency_status',1)->exists()) return response()->json(['errors'=>['currency never exists']], 422);
        $response =  $this->currencyService->deleteCurrency($currency_id, $this->mmCurrency);

        return response()->json([$currency_id]);

    }
}
