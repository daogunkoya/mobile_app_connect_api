<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\MMUser;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return MMUser::get()->toArray();

});









Route::prefix('v1')->group(function () {

//    Route::post('/users', 'App\Http\Controllers\auth_controller@register');
//    Route::post('/users/login', 'App\Http\Controllers\auth_controller@login');
//    Route::post('/users/logout', 'App\Http\Controllers\aauth_controller@logout');

    // Route for user registration
    Route::post('/users', 'App\Http\Controllers\AuthController@register');
// Route for user login
    Route::post('/users/login', 'App\Http\Controllers\AuthController@login');

    Route::post('/users/refresh', 'App\Http\Controllers\aauth_controller@refresh');
    Route::post('/users/me', 'App\Http\Controllers\aauth_controller@me');


      //no token required here


      //fetch todays rate
      Route::get('/rates/today', 'App\Http\Controllers\RateController@todaysRate');

      //calclate commission,total,local from amount
      Route::post('/transactions/calculate', 'App\Http\Controllers\TransactionController@calculateTransaction');

      //fetch commission based on amount
      Route::get('/commissions/value', 'App\Http\Controllers\CommissionController@getCommission');

    //fetch currencies and destination
    Route::get('/currencies/list', 'App\Http\Controllers\CurrencyController@fetchCurrencies');


    /*
    |--------------------------------------------------------------------------
    | Authenticated Routed
    |--------------------------------------------------------------------------
    |
    |
    |
    */

    Route::fallback(function(){
        return response()->json([
            'message' => 'Endpoint not found'], 404);
    });


    //requires token  **************************************






    Route::group(['middleware' => ['auth:api','api']], function() {

        //landing page
        Route::get('/', 'App\Http\Controllers\IndexController@index');

        //transactions
        Route::resource('transactions', 'App\Http\Controllers\TransactionController');

        //for agent customers

//        Route::get('/agent/customers', 'App\Http\Controllers\agent_customer_controller@index');
//        Route::post('/agent/customers', 'App\Http\Controllers\agent_customer_controller@store');
//        Route::put('/agent/customers/{id}', 'App\Http\Controllers\agent_customer_controller@update');


        //for Receivers
        Route::get('/sender/{id}/receivers', 'App\Http\Controllers\ReceiverController@index');
        Route::post('/sender/{id}/receivers', 'App\Http\Controllers\ReceiverController@store');
        Route::put('/sender/{sender_id}/receivers/{receiver_id}', 'App\Http\Controllers\ReceiverController@update');

        //wil be removed on the mobile app
        Route::get('/customer/{id}/receivers', 'App\Http\Controllers\ReceiverController@index');
        Route::post('/customer/{id}/receivers', 'App\Http\Controllers\ReceiverController@store');
        Route::put('/customer/{customer_id}/receivers/{receiver_id}', 'App\Http\Controllers\ReceiverController@update');



        Route::resource('/banks', 'App\Http\Controllers\BankController');
        Route::get('/bank/list', 'App\Http\Controllers\BankController@list');
        Route::resource('/senders', 'App\Http\Controllers\SenderController');


        Route::resource('/commissions', 'App\Http\Controllers\CommissionController');



        Route::resource('/rates', 'App\Http\Controllers\RateController');
        Route::resource('/currencies', 'App\Http\Controllers\CurrencyController');
    });

});



Route::fallback(function(){
return response()->json([
    'message' => 'Endpoint not found'], 404);
});




