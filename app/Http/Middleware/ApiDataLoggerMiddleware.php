<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use App\Models\MMLogConnect;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ApiDataLoggerMiddleware
{
    private $startTime;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info('Running ApiDataLoggerMiddleware');
        return $next($request);
        $this->startTime = microtime(true);
        return $next($request);
    }

    public function terminate($request, $response)
    {
        $laravelStart = defined('LARAVEL_START') ? LARAVEL_START : microtime(true);

        $origin_referer = $request->headers->get('referer');
        $full_url = $request->fullUrl();
        $origin = parse_url($full_url);
        $origin_host = $origin['host'] ?? ($origin['path'] ?? '');

        $request_type = filter_var($origin_host, FILTER_VALIDATE_IP) ? 3 : 2;

        $end_time = microtime(true);

        // Log request headers
        $headers = $request->headers->all();
        $authorizationHeader = $request->headers->get('Authorization') ?? 'NOT_SET';

        // Collect data to store
        $data = [
            'separator' => '--------------------',
            'Time' => gmdate("F j, Y, g:i a"),
            'Origin_url' => $origin_host,
            'Origin_referer' => $origin_referer,
            'api_request' => $request->fullUrl(),
            'store_name' => $request->process_store_name ?? '',
            'Duration' => number_format($end_time - $laravelStart, 3),
            'IP Address' => $request->ip(),
            'store_id' => $request->process_store_id ?? '',
            'response_code' => $response->status(),
            'Method' => $request->method(),
            'Input' => json_encode($request->all(), JSON_PRETTY_PRINT),
            'Headers' => json_encode($headers, JSON_PRETTY_PRINT),
            'Authorization_Bearer_Token' => $authorizationHeader,
            'Responses Output' => substr($response->getContent(), 0, 3000),
        ];

        // Add to content array
        $contents = [
            'request' => $data,
            'response' => [
                'Responses Time' => gmdate("F j, Y, g:i a"),
                'Responses content' => substr($response->getContent(), 0, 3000),
            ]
        ];

        // Save to the database
        MMLogConnect::create([
            'request_type' => $request_type,
            'request_method' => $request->method(),
            'request_origin' => $origin_referer ?? 'NOT_SET',
            'request_destination' => $request->fullUrl() ?? 'NOT_SET',
            'content' => json_encode($contents, JSON_PRETTY_PRINT),
            'request_message' => json_encode($data, JSON_PRETTY_PRINT),
            'response_message' => json_encode($contents['response'], JSON_PRETTY_PRINT),
            'response_code' => $response->status(),
            'user_id' => $request->user()->id ?? ''
        ]);

        // Clean up old logs
        MMLogConnect::where('request_type', 2)->where('count_last_request', '>=', 30)->delete();
        $from_date = Carbon::today()->subDays(30);
        MMLogConnect::where('created_at', '<=', $from_date)->delete();
    }
}





























// namespace App\Http\Middleware;

// use Closure;
// use Illuminate\Support\Facades\URL;
// use Illuminate\Support\Facades\DB;
// use App\Models\MMLogConnect;
// use Carbon\Carbon;
// use App\Services\sqs\sqs_service;

// class ApiDataLoggerMiddleware
// {
//     private $startTime;
//     /**
//     * Handle an incoming request.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @param  \Closure  $next
//     * @return mixed
//     */
//     public function handle($request, Closure $next)
//     {
//         $this->startTime = microtime(true);
//         return $next($request);
//     }
//     public function terminate($request, $response)
//     {
//         $laravelStart = defined('LARAVEL_START') ? LARAVEL_START : microtime(true);
//         $origin_referer = request()->headers->get('referer');
//         $full_url = $request->fullUrl();
//         $origin = parse_url($full_url);
//         $origin_host = '';
//         if (!empty($origin)) {
//             $origin_host = $origin['host'] ?? $origin['path'];
//         }

//         $request_type = 2;                  //general domain request
//         if (filter_var($origin_host, FILTER_VALIDATE_IP)) {
//             $request_type = 3;              //ip request
//         }



//         $end_time = microtime(true);
//         $filename = 'api_datalogger_' . date('d-m-y') . '.log';
//         $data = [];
//         $data ['^^'] =  '^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^';
//         $data ['Time'] =  gmdate("F j, Y, g:i a");
//         $data['Origin_url'] = $origin_host;
//         $data['Origin_referer'] = $origin_referer ;
//         $data['api-request'] =  $request->fullUrl();
//         $data['store_name'] =   $request->process_store_name ?? '';
//         $data['Duration'] = number_format($end_time -  $laravelStart, 3) ;
//         $data['IP Address'] =  $request->ip();
//         $data['store_id'] =      $request->process_store_id ?? '';
//         $data['response_code'] =   $response->status();



//    // $data['Origin_host'] =   $origin_host;
//         $data['Method'] = $request->method();
//         $data_input = json_encode($request->all(), JSON_PRETTY_PRINT);
//         $data_input = strlen($data_input) > 3000 ? substr($data_input, 0, 3000) : $request->all();
//         $data['Input'] = $data_input;
//         $today_req = $data;
//     //$data.= '--------------------------------------------------' . "\n";
//     //$data.= 'Responses Output: ' . $response->getContent() . "\n";
//     // \File::append(storage_path('logs' . DIRECTORY_SEPARATOR . $filename), $data. "\n" . str_repeat("=", 20) . "\n\n");



//         $main_content = optional(MMLogConnect::where('request_type', $request_type)->first())->toArray();
//         $main_content = $main_content ?? [];
//         $entry_content = $main_content ?? [];


//     //update
//         $request_update = $main_content['request_message'] ?? [];
//         $request_update = !empty($main_content['request_message']) ? json_decode($main_content['request_message'], true) : [];
//     //$request_update[] = $data;

//         array_unshift($request_update, $data);

//         $request_update = json_encode($request_update, JSON_PRETTY_PRINT);

//         $response_update = $main_content['response_message'] ?? [];
//         $response_update = !empty($main_content['response_message']) ? json_decode($main_content['response_message'], true) : [];
//         $today_res = [];
//         $today_res ['separator'] =  '-------------------';
//         $today_resp ['Responses Time'] =  gmdate("F j, Y, g:i a");
//         $data_response =  $response->getContent();
//         $data_response =  !empty($data_response) ? substr($data_response, 0, 3000) : $data_response ;
//         $today_resp['Responses content'] = $data_response;

//     //$response_update[]= $today_resp;
//         array_unshift($response_update, $today_resp);
//         $response_update = json_encode($response_update, JSON_PRETTY_PRINT);

//         $users  = $main_content['user_id'] ?? [];
//         $users = !empty($main_content['user_id']) ? json_decode($main_content['user_id'], true) : [];
//         if (!empty($request->user && !empty($users))) {
//             array_unshift($users, $request->user->id_user);
//         }
//         $user_id = !empty($users) ? json_encode($users, JSON_PRETTY_PRINT) : '';


//         $contents = $main_content['content'] ?? [];
//         $contents = !empty($main_content['content']) ? json_decode($main_content['content'], true) : [];
//         $data['separator'] = '--------------------';
//         $data ['Responses Output'] =  $data_response;

//         $todays_input_output = $data;
//     //$contents[] = $data;
//         if (!empty($data)) {
//             array_unshift($contents, $data);
//         }
//     //$contents[] = $contents;
//         $contents = json_encode($contents, JSON_PRETTY_PRINT);




//         //Manage delete
//         //count update on type 2 request
//         MMLogConnect::where('request_type', 2)->where('count_last_request', '<', 31)
//                             ->update(['count_last_request' => DB::raw('count_last_request + 1')]);

//     //delete request type 2 when it is more than 30
//         MMLogConnect::where('request_type', 2)->where('count_last_request', '>=', 30)
//         ->delete();

//         //delete last 30 days logs
//         $from_date = Carbon::today()->subDays(30);
//         MMLogConnect::where('created_at', '<=', $from_date)->delete();





// //Create log for every entry
//         $request_method = $request->method();
//         if ($request_type != 3) {
//                 MMLogConnect::create([
//             'request_type' => 1,
//             'request_method' => $request_method ?? 'GET',
//             'request_origin' => $origin_referer ?? 'NOT_SET',
//             'request_destination' => $request->fullUrl() ?? 'NOT_SET',
//             'content' => json_encode($todays_input_output, JSON_PRETTY_PRINT),
//             'request_message' => json_encode($today_req, JSON_PRETTY_PRINT),
//             'response_message' => json_encode($today_resp, JSON_PRETTY_PRINT),
//             'response_code' => $response->status(),
//             'user_id' => !empty($request->user->id_user) ? $request->user->id_user : ''
//                 ]);
//         }






//         return;
//     }
// }
