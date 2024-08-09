<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Commission\CommissionService;
use App\Repositories\CommissionRepository;
use App\Models\Commission;
use App\Models\Currency;
use App\Models\User;
use App\DTO\CommissionDto;
use App\DTO\UserDto;
use App\Http\Requests\Commissions\CommissionsValidation;
use App\Http\Resources\CommissionResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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


    public function __construct(
                                public CommissionRepository $commissionRepository,
                                CommissionService $comissionService,
                                Commission $commission,
                                Currency $currency, User $user)
    {

                    $this->comissionService = $comissionService;
                    $this->commission = $commission;
                    $this->user = $user;
                    $this->currency = $currency;

    }
    public function index(Request $request):JsonResponse
    {
    
        $fetchedCommissions = $this->commissionRepository->fetchCommissions($request, UserDto::fromEloquentModel(auth()->user()));
       // return $fetchedCommissions ;
        return  CommissionResource::collection(CommissionDto::fromEloquentCollection($fetchedCommissions))
        ->response()->setStatusCode(Response::HTTP_OK);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommissionsValidation $request)
    {

        $rate = auth()->user()->commission()->create($request->validated());
        return (new CommissionResource(
            (CommissionDto::fromEloquentModel( $rate))
        ))->response()->setStatusCode(Response::HTTP_CREATED);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CommissionsValidation $request, $commission_id)
    {



        if (!$this->commission::where('id_commission', $commission_id)->where('commission_status', 1)->exists()) {
            return response()->json(['errors' => ['commission never exists']], 422);
        }


        $response = $this->comissionService->updateCommission($commission_id, $request->all(), $this->commission);

        return response()->json(['commission_id' => $commission_id]);
    }


    public function show(Request $request, $commission_id, CommissionService $commission_service)
    {


        if (!$this->commission::where('id_commission', $commission_id)->where('commission_status', 1)->exists()) {
            return response()->json(['errors' => ['commission never exists']], 422);
        }
                $response = $commission_service->fetchCommission($commission_id, $this->commission, $this->currency, $this->user);

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($commissionId)
    {
        
        $response =  $this->commissionRepository->deleteRate($commissionId);

        return response()->json([$response]);
    }


    public function getCommission(Request $request)
    {
        if (!empty($request->amount)) {
            $amount = $request->amount;

            $res =  $this->commissionRepository->fetchCommissionValue($request->all());

            return response()->json($res);
        }
        return response()->json(['errors' => ['amount' => ['amount is required']]]) ;
    }
}
