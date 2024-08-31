<?php

namespace App\Http\Controllers;

use App\DTO\CurrencyDto;
use Illuminate\Http\Request;
use App\Services\Currency\CurrencyService;
use App\Models\Currency;
use App\Models\User;
use App\Http\Requests\currency\currency_validation;
use App\Http\Resources\CurrencyResource;
use App\Repositories\CurrencyRepository;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CurrencyController extends Controller
{
  
    public $user;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function __construct(
        protected CurrencyService $currencyService, 
        protected Currency $mmCurrency, User $user,
        protected CurrencyRepository $currencyRepository
    )
    {
       
        $this->user = $user;
    }

    public function index(Request $request)
    {
        //
        $fetchCurrency = $this->currencyRepository->fetchCurrencies($request->all());
        
        return  CurrencyResource::collection(CurrencyDto::fromEloquentCollection($fetchCurrency))
        ->response()->setStatusCode(Response::HTTP_OK);
    }

    public function toggleCurrencyStatus(Currency $currency){
       $updatedCurrency =  $this->currencyRepository->toggleCurrencyStatus( $currency);
       return (new CurrencyResource(CurrencyDto::fromEloquentModel($updatedCurrency)))->response()->setStatusCode(Response::HTTP_OK);;


    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(currency_validation $request)
    {


        $response = $this->currencyService->storeCurrency($request->all(), $this->mmCurrency);

        return response()->json(['currency_id' => $response]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update($currency_id, currency_validation $request)
    {

        if (!$this->mmCurrency::where('id_currency', $currency_id)->where('currency_status', 1)->exists()) {
            return response()->json(['errors' => ['currency never exists']], 422);
        }

        $response = $this->currencyService->updateCurrency($currency_id, $request->all(), $this->mmCurrency);

        return response()->json(['currency_id' => $currency_id]);
    }


    public function show(Request $request, $currency_id, CurrencyService $currency_service)
    {

        if (!$this->mmCurrency::where('id_currency', $currency_id)->where('currency_status', 1)->exists()) {
            return response()->json(['errors' => ['currency never exists']], 422);
        }
        $response = $currency_service->fetchCurrency($currency_id, $this->mmCurrency, $this->mmCurrency, $this->user);

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($currency_id)
    {
        //
        if (!$this->mmCurrency::where('id_currency', $currency_id)->where('currency_status', 1)->exists()) {
            return response()->json(['errors' => ['currency never exists']], 422);
        }
        $response = $this->currencyService->deleteCurrency($currency_id, $this->mmCurrency);

        return response()->json([$currency_id]);
    }

    public function fetchCurrencies() :JsonResponse
    {
        $currencies = Currency::where('currency_status', 1)
            ->select('id_currency as currency_code', 'currency_destination')
            ->get()
            ->toArray();

        $count = count($currencies);

        return response()->json([
            'count' => $count,
            'destinations' => $currencies
        ]);
    }

}
