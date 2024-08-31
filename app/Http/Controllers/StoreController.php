<?php

namespace App\Http\Controllers;

use App\Http\Requests\Store\StoreRequest;
use Illuminate\Http\Request;
use App\Models\Store;
use App\DTO\StoreDto;
use App\Http\Resources\StoreResource;   
use Symfony\Component\HttpFoundation\Response;

class StoreController extends Controller
{
    //
    
    public function show(Request $request, Store $store)
    {
        return (new StoreResource(StoreDto::fromEloquentModel($store)))->response()->setStatusCode(Response::HTTP_OK);
    }

public function update(StoreRequest $request, Store $store)
{

    $store->update($request->validated());
    return (new StoreResource(StoreDto::fromEloquentModel($store->fresh())))->response()->setStatusCode(Response::HTTP_OK);
}
    


}
