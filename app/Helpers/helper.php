<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;
use App\Models\store;
use App\Models\Domain;
use App\Models\Currency;
use App\Services\Helper;





//check if store exists

if (!function_exists('check_store_exists')) {
  function check_store_exists($request)
  {

    $input = $request->all();

    // $url = \parse_url(URL::to('/'));
    // $host_domain = $url['host'];

    $host_domain = request()->headers->get('referer');
    $origin_url = parse_url(request()->headers->get('referer'));
    $host_domain = $origin_url['host'] ?? 'localhost';
    $store_url = "https://" . $host_domain;
    $store_exist = false;

    if ($host_domain == 'localhost')  $host_domain = domain_host(url()->current());

    //if worker environment via sqs is requesting processing
    if (!empty($input['process_store_id'])) {
      $domain_exists = Domain::where('store_id', $input['process_store_id'])->where('domain_status', 1)->exists();
      $store_id = $input['process_store_id'];
      if ($domain_exists) {
        $host_domain = Domain::where('store_id', $input['process_store_id'])->orderBy('domain_default', 'desc')->value('domain_host');
        $store_url = "https://" . $host_domain;
        $store_name = store::where('id_store', $store_id)->value('store_name');
        $store_exist = true;
      }
    } else {
      //if user comes with token, then use its store_id
      $user = request()->user();
      if (!empty($user)) {
        $store = initialize_store_info($user['id_user']);
        $store_url = $store['store_url'];
        $store_id = $store['store_id'];
        $store_name = $store['store_name'];
        $store_exist = true;
      } else {
        $domain_exists = Domain::where('domain_host', $host_domain)->where('domain_verified', 1)->where('domain_status', 1)->exists();
        $store_id  = Domain::where('domain_host', $host_domain)->orderBy('domain_default', 'desc')->value('store_id');
        // $store_url = $host_domain == 'localhost'?config('betterdeal.public_url'):$store_url;


        $store_exist = $domain_exists;
        $store_name = store::where('id_store', $store_id)->value('store_name');
      }
    }
    $data = [];

    if ($store_exist == true) {

      $store_version =  store::where('id_store', $store_id)->value('version');
      Session::forget('process_store_id');
      Session::forget('process_store_name');
      Session::forget('process_store_url');
      Session::forget('process_store_version');

      Session::flash('process_store_id', $store_id);
      Session::flash('process_store_url', $store_url);
      Session::flash('process_store_name', $store_name ?? '');
      Session::flash('process_store_version', $store_version ?? '');

      $data = ["process_store_url" => $store_url ?? '', 'process_store_id' => $store_id, 'process_store_name' => $store_name ?? '', 'process_store_version' => $store_version];
    }
    return ['status' => $store_exist, 'data' => $data, 'host_domain' => $host_domain ?? ''];
  }
}







if (!function_exists('image_exist')) {
  function image_exist($file)
  {


    $file = 'http://www.domain.com/somefile.jpg';
    $file_headers = @get_headers($file);
    if (!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
      return false;
    } else {
      return true;
    }
  }
}


//current user_id

if (!function_exists('user_id')) {
  function user_id()
  {
    $user =  user();
    $user_id = $user['id_user'] ?? '';
    return request()->user->id_user ?? $user_id;
  }
}



//fetch url domain host
if (!function_exists('domain_host')) {
  function domain_host($url)
  {

    $domain_host = parse_url($url);
    $domain_host = $domain_host['host'] ?? '';

    return $domain_host;
  }
}


//find number of days apart
if (!function_exists('days_apart')) {
  function days_apart($created_at)
  {

    $days_apart = Carbon::parse($created_at)->diffInDays(Carbon::now()) ?? 0;

    return $days_apart;
  }
}
//fetch url domain host
if (!function_exists('domain_host')) {
  function domain_host($url)
  {

    $domain_host = parse_url($url);
    $domain_host = $domain_host['host'] ?? '';

    return $domain_host;
  }
}


//fetch stripe keys
if (!function_exists('stripe_keys')) {
  function stripe_keys()
  {

    $stripe_public_key = config('betterdeal.stripe_test_public_key') ?? '';
    $stripe_secret_key = config('betterdeal.stripe_test_secret_key') ?? '';

    if (config('betterdeal.payment_stage') == 'live') {

      $stripe_public_key = config('betterdeal.stripe_live_public_key');
      $stripe_secret_key = config('betterdeal.stripe_live_secret_key');
    }

    $payment_keys = ['public_key' => $stripe_public_key, 'secret_key' => $stripe_secret_key];

    return $payment_keys;
  }
}




//authenticate user

if (!function_exists('user')) {

  function user()
  {


    //get user token if it exists
    try {
      $user = request()->user();

      return !empty($user) ? $user->toArray() : [];
    } catch (Exception $e) {
      return [];
    }
  }
}



if (!function_exists('store_user_id')) {

  function store_user_id()
  {


    //get user token if it exists
    try {
      $user = request()->user();

      $user = !empty($user) ? $user->toArray() : [];
      return $user['id_user'] ?? '';
    } catch (Exception $e) {
      return [];
    }
  }
}


if (!function_exists('user_currency')) {

  function user_currency()
  {


    //get user token if it exists
    try {
      $user = request()->user();

      $user = !empty($user) ? $user->toArray() : [];

      $currency_id =  $user['user_active_currency'] ?? '';
      if (empty($currency)) $currency_id = Currency::where('default_currency', 1)->value('id_currency');
      return $currency_id;
    } catch (Exception $e) {
      return [];
    }
  }
}



if (!function_exists('initialize_store_info')) {

    function  initialize_store_info($user_id)
    {


      $store_id = \App\Models\MMUser::where('id_user', $user_id)->value('store_id');


      //resetting store url,store_id and store_name when we know it is admin
      $store_name = store::where('id_store', $store_id)->value('store_name');
      $store_version =  store::where('id_store', $store_id)->value('version');
      $host_domain = Domain::where('store_id', $store_id)->orderBy('domain_default', 'desc')->value('domain_host');
      //  $store_url = $host_domain == 'localhost'?config('betterdeal.public_url'):"https://".$host_domain;
      $store_url = "https://" . $host_domain;

      Session::forget('process_store_id');
      Session::forget('process_store_name');
      Session::forget('process_store_url');
      Session::forget('process_store_version');

      Session::flash('process_store_id', $store_id);
      Session::flash('process_store_url', $store_url);
      Session::flash('process_store_name', $store_name);
      Session::flash('process_store_version', $store_version);

      return ['store_id' => $store_id, 'store_url' => $store_url, 'store_name' => $store_name, 'store_version' => $store_version];
    }

}
