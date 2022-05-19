<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


/*
|--------------------------------------------------------------------------
|Agent Customers
|--------------------------------------------------------------------------
|
|
*/




Route::prefix('v1')->group(function () {

        Route::post('/users', 'App\Http\Controllers\auth_controller@register');
        Route::post('/users/login', 'App\Http\Controllers\auth_controller@login');
        Route::post('/users/logout', 'App\Http\Controllers\aauth_controller@logout');
        Route::post('/users/refresh', 'App\Http\Controllers\aauth_controller@refresh');
        Route::post('/users/me', 'App\Http\Controllers\aauth_controller@me');


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

        Route::group(['middleware' => ['jwt.verify','api']], function() {



            //for agent customers

            Route::get('/agent/customers', 'App\Http\Controllers\agent_customer_controller@index');
            Route::post('/agent/customers', 'App\Http\Controllers\agent_customer_controller@store');
            Route::put('/agent/customers/{id}', 'App\Http\Controllers\agent_customer_controller@update');


            //for Receivers

            Route::get('/customer/{id}/receivers', 'App\Http\Controllers\receiver_controller@index');
            Route::post('/customer/{id}/receivers', 'App\Http\Controllers\receiver_controller@store');
            Route::put('/customer/{customer_id}/receivers/{receiver_id}', 'App\Http\Controllers\receiver_controller@update');
        


            Route::resource('/banks', 'App\Http\Controllers\banks_controller');
            Route::get('/bank/list', 'App\Http\Controllers\banks_controller@list');
            Route::resource('/senders', 'App\Http\Controllers\senders_controller');
        });

});
  
  

Route::fallback(function(){
    return response()->json([
        'message' => 'Endpoint not found'], 404);
  });  
  