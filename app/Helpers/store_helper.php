<?php

use Illuminate\Support\Facades\Session;
use App\Models\store;
use App\Models\bd_domain;

// for creating image link url
if(! function_exists('image_url')){

    function image_url(){

        $store_id = session()->get('process_store_id')??request()->process_store_id;
        $image_url = config('betterdeal.image_url').'store/'.$store_id.'/';;
        return  $image_url ;
    }
}




//image folder name attached with store_id
if(! function_exists('image_folder')){

    function image_folder(){

        $store_id = session()->get('process_store_id')??request()->process_store_id;
        $image_folder = config('betterdeal.image_folder').'store/'.$store_id.'/';;
        return  $image_folder ;
    }
}



//function name for  store_id
if(! function_exists('store_id')){
    function store_id(){
      return session()->get('process_store_id')??request()->process_store_id;
    }
}


//function name for store_url
if(! function_exists('store_url')){
    function store_url(){
      return session()->get('process_store_url')??request()->process_store_url;
    }
}


//function name for store_version
if(! function_exists('store_version')){
    function store_version(){
      return session()->get('process_store_version')??request()->process_store_version;
    }
}

//function name for store_name
if(! function_exists('store_name')){
    function store_name(){
      return store::where('id_store', store_id())->value('store_name');
    }
}


//initialize new store info
if(! function_exists('initialise_store')){
    function initialise_store($store_id){


    if(store::where('id_store', $store_id)->exists()){
            $host_domain = bd_domain::where('store_id', $store_id)->orderBy('domain_default', 'desc')->value('domain_host');
            $store_url = "https://".$host_domain;
            $store_name = store::where('id_store', $store_id)->value('store_name');

            Session::forget('process_store_id');
            Session::forget('process_store_name');
            Session::forget('process_store_url');

            Session::flash('process_store_id', $store_id);
            Session::flash('process_store_url', $store_url);
            Session::flash('process_store_name', $store_name);
    }

            return;

    }
}





 function get_doc_fromS3(){
    try{
        $response =   \App\Services\S3Handler::connect()->putObject([
                    'Bucket' => config('betterdeal.bucket_url'),
                    'Key'    =>'templates/general.blade.php'

                ]);
      ////self::log(['request'=>$path,'response'=>$response]);
     return $response;

} catch(S3Exception $e){
    var_dump($e->getMessage());
//return self::log(['request'=>self::$tempFilePath,'response'=>$e->getMessage()]);
} catch (Exception $e) {
    var_dump($e->getMessage());
//return self::log(['request'=>self::$tempFilePath,'response'=>$e->getMessage()]);
}


}
