<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Repositories\OutstandingPaymentRepository;

use App\DTO\OutstandingPaymentDto;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\OutstandingPaymentResource;

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
}
