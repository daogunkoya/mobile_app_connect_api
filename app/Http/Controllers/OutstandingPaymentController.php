<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OutstandingPayment;
use App\Repositories\OutstandingPaymentRepository;

use App\DTO\OutstandingPaymentDto;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\OutstandingPaymentResource;
use App\Services\Log\LoggingService; 
use App\Http\Requests\Outstanding\OutstandingRequest;
use App\Services\Outstanding\OutstandingPaymentService;
use App\Payment\CreatePaymentForOutstanding;
use App\Permissions\Abilities;

class OutstandingPaymentController extends Controller
{

    public function __construct(protected OutstandingPaymentRepository $OutstandingPaymentRepository) {

    }
    
    //
    public function index(Request $request)
    {
        $fetchOutstadingPayment = $this->OutstandingPaymentRepository->fetchOutstandingPayment($request->all());
        
        return  OutstandingPaymentResource::collection(OutstandingPaymentDto::fromEloquentCollection($fetchOutstadingPayment))
        ->response()->setStatusCode(Response::HTTP_OK);
    }

    public function addOutstandingPayment(Request $request)
    {
       
    //    if($request->amount>=$request->total){
    //          Outstanding::whereUser_id($request->agent_id)
    //                     ->update(['transaction_paid'=>1,
    //                     'admin_id' => Auth::user()->type==='admin'?Auth::id():0,
    //                     'manager_id' => Auth::user()->type==='manager'?Auth::id():0,]);
    //         PartPayment:: whereUser_id($request->agent_id)
    //                  ->where('payment_type','transaction')->update(['fully_paid'=>1]);    
    //      }
       
    //     PartPayment::create([
    //             'user_id' =>$request->agent_id,
    //             'amount'=>  $request->amount,
    //             'payment_type'=>'transaction',
    //             'admin_payment_id' => Auth::user()->type==='admin'?Auth::id():0,
    //             'manager_id' => Auth::id(),
    //             'fully_paid'=>$request->amount>=$request->total?1:0
    //     ]);

    //     return redirect()->route('outstandings.index');
    }

    public function outstandingPayment(Request $request)    
    {
        $fetchOutstadingPayment = $this->OutstandingPaymentRepository->fetchOutstandingPayment($request->all());
        
        return  OutstandingPaymentResource::collection(OutstandingPaymentDto::fromEloquentCollection($fetchOutstadingPayment))
        ->response()->setStatusCode(Response::HTTP_OK);
    }
    

    public function makePayment(OutstandingRequest $request, OutstandingPaymentService $outstandingService, CreatePaymentForOutstanding $createPaymentForOutstanding, LoggingService $loggingService)
    {
          
        $outstandingPayment = null;

        // Validate the request and determine the type of payment
        if (!empty($request->outstanding_id)) {
            if ($request->payment_type === "Transaction") {
                $outstandingPayment = $outstandingService->updateTransactionPaymentStatus($request->outstanding_id);
            } elseif ($request->payment_type === "Commission") {
                $outstandingPayment = $outstandingService->updateCommissionPaymentStatus($request->outstanding_id);
            }
    } else if ($request->outstanding_amount > 0) {
        if ($request->payment_type === "Transaction") {
            $outstandingPayment = $outstandingService->processOutstandingTransactionPayment($request->user_id, $request->outstanding_amount);
        } elseif ($request->payment_type === "Commission") {
            $outstandingPayment = $outstandingService->processOutstandingCommissionPayment($request->user_id, $request->outstanding_amount);
        }
    }

    // Ensure that we only proceed if an OutstandingPayment was created or updated
    if ($outstandingPayment) {
        // Log the activity
        $loggingService->logActivity($outstandingPayment, "outstanding_payment", "create");

        return (new OutstandingPaymentResource(OutstandingPaymentDto::
        fromEloquentModel($outstandingPayment->fresh())))->response()->setStatusCode(Response::HTTP_CREATED);

        // Create payment record associated with the outstanding payment
        $createPaymentForOutstanding->createPaymentForOutstanding($outstandingPayment);
    } else {
        // Handle the case when no OutstandingPayment was found/created
        throw new \Exception("Outstanding Payment could not be processed.");
    }
    }
    
}
