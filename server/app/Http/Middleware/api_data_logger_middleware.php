<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use App\Models\mm_log_connect;
use Carbon\Carbon;
use App\Services\sqs\sqs_service;
    class api_data_logger_middleware
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
        $this->startTime = microtime(true);
        return $next($request);
        //wrapp base url 
        //return $next($request->merge(["request_url"=>$request->fullUrl(),'base_url'=>URL::to('/')]));
    }
    public function terminate($request, $response)

    { 
       
        
        $origin_url = request()->headers->get('referer');
        $origin = parse_url(request()->headers->get('referer'));
        $origin_host = $origin['host']??'';
      

    // if (env('API_DATALOGGER', true)) {
    //     return;
    // }


    $end_time = microtime(true);
    $filename = 'api_datalogger_' . date('d-m-y') . '.log';
    $data = [];
    $data ['^^'] =  '^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^';
    $data ['Time'] =  gmdate("F j, Y, g:i a");
    $data['Origin_url'] =  $origin_url;
    $data['api-request']=  $request->fullUrl();
    $data['store_name']=   $request->process_store_name??'';
    $data['Duration'] = number_format($end_time - LARAVEL_START, 3) ;
    $data['IP Address']=  $request->ip();
    $data['store_id']=      $request->process_store_id??'';
    
   
   
   // $data['Origin_host'] =   $origin_host;
    $data['Method'] = $request->method();
    $data_input = json_encode($request->all(), JSON_PRETTY_PRINT);
    $data_input = strlen($data_input) > 3000?substr($data_input,0, 3000 ):$request->all();
    $data['Input'] = $data_input;
    $today_req = $data;
    //$data.= '--------------------------------------------------' . "\n";
    //$data.= 'Response Output: ' . $response->getContent() . "\n";
    // \File::append(storage_path('logs' . DIRECTORY_SEPARATOR . $filename), $data. "\n" . str_repeat("=", 20) . "\n\n");



    $main_content = optional(mm_log_connect::where('request_type', 2)->first())->toArray();
    $main_content =$main_content??[];
    $entry_content =$main_content??[];
    
    
    //update
    $request_update = $main_content['request_message']??[]; 
    $request_update =!empty($main_content['request_message'])?json_decode($main_content['request_message'], true):[];
    //$request_update[] = $data;
    
     array_unshift($request_update , $data);

    $request_update= json_encode($request_update, JSON_PRETTY_PRINT);
    
    $response_update = $main_content['response_message']??[];
    $response_update =!empty($main_content['response_message'])?json_decode($main_content['response_message'], true):[];
    $today_res = [];
    $today_res ['separator'] =  '-------------------';
    $today_resp ['Response Time'] =  gmdate("F j, Y, g:i a");
    $data_response=  $response->getContent();
    $data_response =  !empty($data_response)?substr($data_response,0, 3000 ): $data_response ;
    $today_resp['Response content'] =$data_response;
    
    //$response_update[]= $today_resp;
     array_unshift($response_update , $today_resp);
    $response_update= json_encode($response_update, JSON_PRETTY_PRINT);

    $users  = $main_content['user_id']??[];
    $users =!empty($main_content['user_id'])?json_decode($main_content['user_id'], true):[];
    if(!empty($request->user->id_user)) array_unshift($users , $request->user->id_user??'');
    $user_id= json_encode($users, JSON_PRETTY_PRINT);
   

    $contents = $main_content['content']??[];
    $contents =!empty( $main_content['content'])?json_decode( $main_content['content'], true):[];
    $data['separator']= '--------------------';
    $data ['Response Output'] =  $data_response;

    $todays_input_output = $data;
    //$contents[] = $data;
    if(!empty($data)) array_unshift($contents , $data);
    //$contents[] = $contents; 
    $contents= json_encode($contents, JSON_PRETTY_PRINT);



    //this function is suspended bcos of large content of a single block during processing--fetch of a a content
   
        // mm_log_connect::updateOrCreate(
        //     [ 'created_at' => $dt],
        // [
        //     'request_type' => 2,
        //     'content' => $contents,
        //     'request_message' => $request_update,
        //     'response_message' => $response_update,
        //     'user_id' => $request->user
        // ]);
        
        //for s3 storage and monitor daily request, combine request of type 2 until it is 30 & then delete the whole content
        $today_id = mm_log_connect::where('request_type', 2)->value('id_log');
        if(!empty($today_id)){
            mm_log_connect::where('id_log', $today_id)->update( [
                                                                    'request_type' => 2,
                                                                    'content' => $contents,
                                                                    'request_message' => $request_update,
                                                                    'response_message' => $response_update,
                                                                    'user_id' => $request->user
            ]);
        }else{
            mm_log_connect::create( [
                'store_id' =>$request->process_store_id??'',
                'store_name' =>$request->process_store_name??'',
                'request_type' => 2,
                'content' => $contents,
                'request_message' => $request_update,
                'response_message' => $response_update,
                'user_id' => $request->user     
]);
        }


        //count update on type 2 request
        mm_log_connect::where('request_type', 2)->where('count_last_request', '<', 31)
                            ->update(['count_last_request'=>DB::raw('count_last_request + 1')]);
        
    //delete request type 2 when it is more than 30
        mm_log_connect::where('request_type', 2)->where('count_last_request', '>=', 30)
        ->delete();

        //delete last 30 days logs
        $from_date = Carbon::today()->subDays(30);
        mm_log_connect::where('created_at', '<=', $from_date)->delete();



        

//Create log for every entry
        
        mm_log_connect::create([
            'store_id' =>$request->process_store_id??'',
            'store_name' =>$request->process_store_name??'',
            'request_type' => 1,
            'content' => json_encode($todays_input_output, JSON_PRETTY_PRINT),
            'request_message' => json_encode($today_req, JSON_PRETTY_PRINT),
            'response_message' => json_encode($today_resp, JSON_PRETTY_PRINT),
            'user_id' => $request->user->id_user??''
        ]);

       // sqs_service::job(['type' => 'daily_request_tos3','data'=>NULL]);


       //to be continue later
       //send ogs to s3
      //  \App\Services\Helper::move_image_toS3($contents);

    
        return;

  

    }
}
