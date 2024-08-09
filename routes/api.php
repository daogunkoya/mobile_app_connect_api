<?php

use App\Http\Controllers\AddressFinderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\TransactionReportController;
use App\Http\Controllers\MemberController;
// use App\Http\Controllers\Auth\ForgotPasswordController;
// use App\Http\Controllers\Auth\ResetPasswordController;

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
    return User::get()->toArray();

});









Route::prefix('v1')->group(function () {

//    Route::post('/users', 'App\Http\Controllers\auth_controller@register');
//    Route::post('/users/login', 'App\Http\Controllers\auth_controller@login');
//    Route::post('/users/logout', 'App\Http\Controllers\aauth_controller@logout');

    // Route for user registration
    Route::post('/users', 'App\Http\Controllers\AuthController@register');
// Route for user login
    Route::post('/users/login', 'App\Http\Controllers\AuthController@login');
    Route::post('password/email', [PasswordResetController::class, 'sendResetLinkEmail']);
    // Route::post('password/reset', [PasswordResetController::class, 'reset'])->name('password.reset');;

    // Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    // Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    // Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    // Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

//    Route::post('/users/refresh', 'App\Http\Controllers\aauth_controller@refresh');
//    Route::post('/users/me', 'App\Http\Controllers\aauth_controller@me');


      //no token required here


      //fetch todays rate
      Route::get('/rates/today', 'App\Http\Controllers\RateController@todaysRate');

      //calclate commission,total,local from amount
      Route::post('/transactions/calculate', 'App\Http\Controllers\TransactionController@calculateTransaction');

      //fetch commission based on amount
      Route::get('/commissions/value', 'App\Http\Controllers\CommissionController@getCommission');

    //fetch currencies and destination
    Route::get('/currencies/list', 'App\Http\Controllers\CurrencyController@fetchCurrencies');

    Route::get('/address-finder', [AddressFinderController::class, 'addressFinder'])->name('address-finder');
    Route::get('/address-by-udprn', [AddressFinderController::class, 'addressByUDPRN'])->name('address-by-udprn');

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

        Route::get('/members', [MemberController::class, 'index']);
        Route::post('transactions/report/generate', [TransactionReportController::class, 'generateReport']);


        Route::post('password/reset', [PasswordResetController::class, 'reset'])->name('password.reset');;

        Route::post('/transactions/transfer/breakdown', 'App\Http\Controllers\TransactionController@calculateTransaction');
        Route::get('/transaction/{transaction:id_transaction}/download', [\App\Http\Controllers\TransactionController::class, 'downloadReceipt'])->name('transaction.download');
        Route::post('/transaction/{transaction:id_transaction}/report', 'App\Http\Controllers\TransactionController@reportTransaction');

        //landing page
        Route::get('/', App\Http\Controllers\IndexController::class)->name('home');

        //transactions
        Route::resource('transactions', 'App\Http\Controllers\TransactionController');
        Route::get('transactions/{transaction:id_transaction}',
            'App\Http\Controllers\TransactionController@show')->name('transactions.show');

        //for agent customers

//        Route::get('/agent/customers', 'App\Http\Controllers\agent_customer_controller@index');
//        Route::post('/agent/customers', 'App\Http\Controllers\agent_customer_controller@store');
//        Route::put('/agent/customers/{id}', 'App\Http\Controllers\agent_customer_controller@update');


        //for Receivers
        Route::get('/sender/{sender:id_sender}/receivers', 'App\Http\Controllers\ReceiverController@index')->name('receivers.index');
        Route::post('/sender/{sender}/receivers', 'App\Http\Controllers\ReceiverController@store')->name('receivers.store');
        Route::put('/sender/{sender_id}/receivers/{receiver:id_receiver}', 'App\Http\Controllers\ReceiverController@update')->name('receivers.update');
        Route::get('/sender/{sender_id}/receivers/{receiver_id}', 'App\Http\Controllers\ReceiverController@show');
        Route::delete('/sender/{sender_id}/receivers/{receiver_id}', 'App\Http\Controllers\ReceiverController@destroy');

        //wil be removed on the mobile app
        Route::get('/customer/{id}/receivers', 'App\Http\Controllers\ReceiverController@index');
        Route::post('/customer/{id}/receivers', 'App\Http\Controllers\ReceiverController@store');
        Route::put('/customer/{customer_id}/receivers/{receiver_id}', 'App\Http\Controllers\ReceiverController@update');



        // Route::resource('/banks', 'App\Http\Controllers\BankController');
        Route::get('/bank/list', 'App\Http\Controllers\BankController@list');
        Route::resource('/senders', 'App\Http\Controllers\SenderController');
        Route::put('/senders/{sender:id_sender}', 'App\Http\Controllers\SenderController@update')->name('update_sender');
//        Route::post('/senders/{sender:id_sender}/receiver', 'App\Http\Controllers\SenderController@store')->name('create_receiver');

        Route::resource('/commissions', 'App\Http\Controllers\CommissionController');
        Route::delete('/commissions/{commission:id_commission}', 'App\Http\Controllers\CommissionController@destroy');



        Route::resource('/rates', 'App\Http\Controllers\RateController');
        Route::resource('/currencies', 'App\Http\Controllers\CurrencyController');
    });

});



Route::fallback(function(){
return response()->json([
    'message' => 'Endpoint not found'], 404);
});




