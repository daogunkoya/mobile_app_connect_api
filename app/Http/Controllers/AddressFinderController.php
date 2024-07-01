<?php

namespace App\Http\Controllers;

use App\DTO\IdealPostCodeService\AddressAutoSuggestDTO;
use App\DTO\IdealPostCodeService\AddressByUDPRNDTO;
use App\Services\ThirdPartyServices\IdealPostCodeService\Contracts\AddressVerificationService\AddressVerificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use  App\Http\Responses\JsonResponder;
//use JsonResponder;

class AddressFinderController extends Controller
{
    public function __construct(public readonly AddressVerificationService $addressVerificationService)
    {
    }

    public function addressFinder(Request $request)
    {

        $response = $this->addressVerificationService
            ->setRequestUrl('autocomplete/addresses')
            ->using(AddressAutoSuggestDTO::class)
            ->setRequestParams(['query' => $request->query('address')])
            ->execute();

       // return $response;
        return JsonResponder::success($response, 'Address Data');
       // return response()->json( ['data' => $response]);
    }

    public function addressByUDPRN(Request $request): JsonResponse
    {
        $response = $this->addressVerificationService
            ->using(AddressByUDPRNDTO::class)
            ->setRequestUrl("udprn/{$request->query('udprn')}")
            ->setRequestParams([])
            ->execute();

       // return $response()->json( [$response]);
       return JsonResponder::success($response, 'Address Data');
    }
}
