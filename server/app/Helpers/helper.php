<?php
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;
use App\Models\mm_store;
use App\Models\mm_domain;
use App\Services\Helper;




//check if store exists

if (! function_exists('check_store_exists')){
     function check_store_exists($request){

      $input = $request->all();
       
      // $url = \parse_url(URL::to('/'));
      // $host_domain = $url['host'];

      $host_domain = request()->headers->get('referer');
      $origin_url = parse_url(request()->headers->get('referer'));
      $host_domain = $origin_url['host']??'localhost';
      $store_url ="https://".$host_domain;
      $store_exist = false;
     
     if($host_domain == 'localhost')  $host_domain = domain_host( url()->current());
     
  //if worker environment via sqs is requesting processing
     if(!empty($input['process_store_id'])){
      $domain_exists = mm_domain::where('store_id', $input['process_store_id'])->where('domain_status', 1)->exists();  
      $store_id = $input['process_store_id'];
      if($domain_exists){
        $host_domain = mm_domain::where('store_id', $input['process_store_id'])->orderBy('domain_default', 'desc')->value('domain_host'); 
        $store_url = "https://".$host_domain; 
        $store_name = mm_store::where('id_store', $store_id)->value('store_name');
        $store_exist = true;
       
      }
     }else{
          //if user comes with token, then use its store_id 
          $user = Helper::auth_token();
          if(!empty($user)){
              $store = Helper::initialize_store_info($user['id_user']); 
              $store_url = $store['store_url']; $store_id = $store['store_id'];$store_name = $store['store_name'] ;
              $store_exist = true;
                  }else{
                    $domain_exists = mm_domain::where('domain_host', $host_domain)->where('domain_verified', 1)->where('domain_status', 1)->exists();
                    $store_id  = mm_domain::where('domain_host', $host_domain)->orderBy('domain_default', 'desc')->value('store_id');
                    // $store_url = $host_domain == 'localhost'?config('betterdeal.public_url'):$store_url;
                    
                    
                       $store_exist = $domain_exists;
                       $store_name = mm_store::where('id_store', $store_id)->value('store_name');
                  }
        
     }
                $data = [];

                if($store_exist == true){
                  
                 $store_version =  mm_store::where('id_store', $store_id)->value('version');
                  Session::forget('process_store_id'); 
                  Session::forget('process_store_name');
                  Session::forget('process_store_url');
                  Session::forget('process_store_version');
         
                  Session::flash('process_store_id', $store_id);
                  Session::flash('process_store_url', $store_url);
                  Session::flash('process_store_name', $store_name??'');
                  Session::flash('process_store_version', $store_version??'');
                 
                  $data = ["process_store_url"=>$store_url??'', 'process_store_id'=>$store_id, 'process_store_name'=>$store_name??'', 'process_store_version' => $store_version];
                }
                return ['status' => $store_exist, 'data' =>$data, 'host_domain' =>$host_domain??''];


    }
  }







if (! function_exists('image_exist')){
     function image_exist($file){


          $file = 'http://www.domain.com/somefile.jpg';
          $file_headers = @get_headers($file);
          if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
              return false;
          }
          else {
              return true;
          }
    }
  }


  //current user_id

  if(! function_exists('user_id')){
    function user_id(){
      return request()->user->id_user??''; 
    }
}



//fetch url domain host
  if(! function_exists('domain_host')){
    function domain_host($url){

        $domain_host = parse_url($url);
        $domain_host = $domain_host['host']??'';

      return $domain_host; 
    }
}


//find number of days apart
  if(! function_exists('days_apart')){
    function days_apart($created_at){

      $days_apart = Carbon::parse($created_at)->diffInDays(Carbon::now())??0;

        return $days_apart; 
    }
}
//fetch url domain host
  if(! function_exists('domain_host')){
    function domain_host($url){

        $domain_host = parse_url($url);
        $domain_host = $domain_host['host']??'';

      return $domain_host; 
    }
}


//fetch stripe keys
  if(! function_exists('stripe_keys')){
    function stripe_keys(){

        $stripe_public_key = config('betterdeal.stripe_test_public_key')??'';
        $stripe_secret_key = config('betterdeal.stripe_test_secret_key')??'';
        
        if(config('betterdeal.payment_stage')=='live'){
          
            $stripe_public_key = config('betterdeal.stripe_live_public_key');
            $stripe_secret_key = config('betterdeal.stripe_live_secret_key');
        }

        $payment_keys = ['public_key'=> $stripe_public_key, 'secret_key'=>$stripe_secret_key ];

        return $payment_keys;
     
    }
}













    







