<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\mm_bank;

class banks_controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return mm_bank::get();
    }



    // public function none()
    // {
    //     return optional(mm_bank::where('status', 'b')->pluck('name'))->toArray();
    // }

   
    public function list()
    {
        $list_bank =  optional(mm_bank::where('bank_category', 'b')->select('name as value', 'id_bank as key')->get())->toArray();
        $count_bank =  mm_bank::where('bank_category', 'b')->count();
        $list_pickup =  optional(mm_bank::where('bank_category', 'p')->select('name as value', 'id_bank as key')->get())->toArray();
        $count_pickup =  mm_bank::where('bank_category', 'p')->count();
        $proof_id_list =  optional(mm_bank::where('bank_proof_identity', '1')->select('name as value', 'id_bank as key')->get())->toArray();

        $option_list =  optional(mm_bank::where('transfer_type', '3')->select('name as value', 'transfer_type_key as key')->get())->toArray();
      

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

            mm_bank::create([
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
