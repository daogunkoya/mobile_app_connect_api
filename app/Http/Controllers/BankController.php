<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Bank::get();
    }



    // public function none()
    // {
    //     return optional(Bank::where('status', 'b')->pluck('name'))->toArray();
    // }


    public function list()
    {
        $list_bank =  optional(Bank::where('bank_category', 'b')->select('name as value', 'id_bank as key')->get())->toArray();
        $count_bank =  Bank::where('bank_category', 'b')->count();
        $list_pickup =  optional(Bank::where('bank_category', 'p')->select('name as value', 'id_bank as key')->get())->toArray();
        $count_pickup =  Bank::where('bank_category', 'p')->count();
        $proof_id_list =  optional(Bank::where('bank_proof_identity', '1')->select('name as value', 'id_bank as key')->get())->toArray();

        $option_list =  optional(Bank::where('transfer_type', '3')->select('name as value', 'transfer_type_key as key')->get())->toArray();


        return response()->json(['bank_count'=>$count_bank,
                                 'bank'=>$list_bank,
                                 'bank_pickup'=>$count_pickup,
                                 'list_pickup'=>$list_pickup,
                                'proof_id'=>$proof_id_list,
                                'transfer_type_list'=>$option_list]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        foreach($request->bank as $bank){

            Bank::create([
                    'store_id'=>store_id(),
                    'name'=> $bank,
                    'transfer_type' => 1,
                    'status'=> 'b'

            ]);
        }

        return;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
