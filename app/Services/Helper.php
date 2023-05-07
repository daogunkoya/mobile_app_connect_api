<?php
namespace App\Services;
use JWTAuth;
use Exception;
use App\Models\bd_item_connect;
use App\Models\MMUser_connect;
use App\Models\bd_item_deal;
use App\Models\MMUser;
use App\Models\Domain;
use App\Models\store;
use App\Models\bd_order;
use App\Models\bd_item_discussion;
use App\Models\bd_item_comment;
use App\Models\bd_image;
use App\Models\bd_group;
use App\Models\bd_log_connect;
use Aws\S3\Exception\S3Exception;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use App\Services\sqs\sqs_service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;



class Helper{


    public static function auth_token(){


        //get user token if it exists
            try{
                $user = JWTAuth::parseToken()->authenticate();

                return !empty($user)?$user->toArray():[];
            }catch (Exception $e) {
                if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                    return [];
                }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                    return [];
                }else{
                    return [];
                }
            }
        }

        public static function user_id(){
            $user = self::auth_token();
            if(!empty($user)) return $user['id_user'];
            return '';

            //get user token if it exists

         }


//wrap list group from array into structure of category brand & stores
        public static function filter_group($list_group){

            // $categories =  bd_group::whereIn('id_group',$list_group)->where('group_type',2)->select('id_group')->get()->toArray();
            // $brands =  bd_group::whereIn('id_group',$list_group)->where('group_type',3)->pluck('id_group')->toArray();
            // $stores =  bd_group::whereIn('id_group',$list_group)->where('group_type',4)->pluck('id_group')->toArray();

            $all = [];

            foreach($list_group as $group){

                $result = bd_group::where('id_group',$group)->select('id_group as group_id','group_type','group_title',"group_slug")->first();
               if(!empty($result)){
                $result = $result->toArray();

                if($result['group_type'] ==2)  $categories[] = $result['group_id'];
                if($result['group_type'] ==3)  $brands[] = $result['group_id'];
                if($result['group_type'] ==4)  $stores[] = $result['group_id'];
                $all[] = $result;
                }
            }

           //var_dump($brands);

            return ['all'=>$all, 'categories' => $categories??[] , 'brands'=>$brands??[], 'stores' => $stores??[]];
        }


        public static function get_row_item_connect($cursor){
            if(!bd_item_connect::where('id_connect',$cursor)->exists())  return response()->json(['errors'=>['cursor is not valid'], 404]);
            if($cursor)  return  bd_item_connect::where('id_item',$cursor)->value('row_number');

        }

        public static function get_row_user_connect($cursor){
            if(!MMUser_connect::where('id_connect',$cursor)->exists())  return response()->json(['errors'=>['cursor is not valid'], 404]);
            if($cursor)  return  MMUser_connect::where('id_item',$cursor)->value('row_number');

        }

        public static function get_row_deal($cursor){
            if(!bd_item_deal::where('id_item',$cursor)->exists())  return response()->json(['errors'=>['cursor is not valid'], 404]);
            if($cursor)  return  bd_item_deal::where('id_item',$cursor)->value('row_number');

        }

        public static function get_row_discussion($cursor){
            if(!bd_item_discussion::where('id_item',$cursor)->exists())  return response()->json(['errors'=>['cursor is not valid'], 404]);
            if($cursor)  return  bd_item_discussion::where('id_item',$cursor)->value('row_number');

        }


        public static function get_row_user($cursor){
            if(!MMUser::where('id_item',$cursor)->exists())  return response()->json(['errors'=>['cursor is not valid'], 404]);
            if($cursor)  return  MMUser::where('id_item',$cursor)->value('row_number');

        }

        public static function get_row_user_alert($cursor){
            if(!MMUser_alert::where('id_alert',$cursor)->exists())  return response()->json(['errors'=>['cursor is not valid'], 404]);
            if($cursor)  return  MMUser_alert::where('id_alert',$cursor)->value('row_number');

        }

//get key of an item in an array--used for pagination
        public static function find_key(Array $array_content,$searchable_column,$cursor){
            if(is_array($array_content)){
                foreach($array_content as $key=>$item) {
                    if($item[$searchable_column] == $cursor){
                        return $key;
                    }
                }
            }
            return false;
        }


 //array content of store and brand
        public static function type_group(Array $groups){
            $result = [];

                if(!empty($groups)){
                    foreach($groups as $group){
                        if($group['group_type']==2) $result['categories'][] = $group;
                        if($group['group_type']==3) $result['brand'] = $group;
                        if($group['group_type']==4) $result['store'] = $group;
                    }
                }
                return $result??[];
        }

 //array content of store and brand
        public static function with_one_category ($category, Array $groups){
            $result = [];

                if(!empty($groups)){
                    foreach($groups as $group){
                        if( ($group['group_type']==2 &&  $group['group_id']== $category) || ($group['group_type']==3
                                    || $group['group_type']==4) )  $result[] = $group;
                    }
                }
                return $result??[];



        }



        //  nested sorting

        public static function nest_sort(Array $arr, $parent_nest, $child_nest_column ){

              usort($arr, function($a, $b) use($parent_nest, $child_nest_column) {
                $retval = $a[$parent_nest] <=> $b[$parent_nest];
                if ($retval == 0) {
                    $retval =   $a[$parent_nest][$child_nest_column] <=> $b[$parent_nest][$child_nest_column];     //deep nested
                }

                return $retval;

            });
            return $arr;
        }


        // deep nested sorted of column 1 sort and nest parent & child sort

        public static function deep_sort(Array $arr, $sort_column1,$parent_nest, $child_nest_column ){

            usort($arr, function($a, $b) {
                $return_value = $a['sort_column1'] <=> $b['sort_column1'];
                if ($return_value == 0) {
                    $return_value =  $a['parent_nest']['child_nest_column'] <=> $b['parent_nest']['child_nest_column'];     //deep nested
                }
                return $return_value;
            });
        }


        // two column nest

        public static function sort_2(Array $arr, $col1,$col2 ){

           // Obtain a list of columns
                    foreach ($arr as $key => $row) {
                        $colA[$key]  = $row[$col1];
                        $colB[$key] = $row[$col2];
                    }

                    // Sort the data with volume descending, edition ascending
                    array_multisort($colA, SORT_ASC, $colB, SORT_ASC, $arr);

                    return $arr;
        }


        //sort helper for an array --
        public static function sort($arr, $column, $sort_type = 2){
             usort($arr, function($a, $b)use($column,$sort_type) {
                return  $sort_type == 2?$b[$column] <=> $a[$column]: $a[$column] <=> $b[$column];
            });

            return $arr;
        }



        //sort date

       public static function date_compare($list, $date_column)
            {


                usort($list, function($a, $b)use($date_column)
                                        {
                                            $t1 = strtotime($a[$date_column]);
                                            $t2 = strtotime($b[$date_column]);
                                            return $t2 - $t1;
                                        });

                return $list;

            }




        public static function group_first_in($slug, Array $groups){


            if(!empty($groups)){
                $slug_key = array_search($slug, array_column($groups, 'group_slug'));

                if($slug_key !== FALSE){

                    $search_item = $groups[$slug_key];
                    unset($groups[$slug_key]);
                    array_unshift($groups,$search_item );

                    return $groups;
                }
            }
        }

        public static function group_first_with_id(Array $request_group, Array $groups){


            if(!empty($request_group)){

                foreach($request_group as $key=>$request){

                    $group_key = array_search($request, array_column($groups,'group_id'));

                    if($group_key !== FALSE){
                        $main_item = $groups[$group_key];
                        unset($groups[$group_key]);
                        array_unshift($groups, $main_item );
                    }

                }

            }

            return $groups;
        }


        public static function group_id_to_title(Array $groups){
                $title_list= [];
                if(!empty($groups)){
                    foreach($groups as $group){

                        $group_title = bd_group::where('id_group', $group)->value('group_title');
                        $title_list[] = $group_title;
                    }

                    return implode(', ', $title_list);
                }
        }


    //update connect data for deal/discussion for publish discussion  with possible existing discussion
    public static function connect_update(String $item_id,  $item_type, $connect_status = 1){

        switch($item_type){

            case 1:
                 $list_group =  bd_item_deal::where('id_item', $item_id)->value('list_group');
            break;
            case 2:
                 $list_group =  bd_item_discussion::where('id_item', $item_id)->value('list_group');
            break;
        }


        $list_group = json_decode($list_group, true);
        $list_group = array_column($list_group, 'group_id');


        if(!empty($list_group)){
                foreach($list_group as $connect_id){
                    bd_item_connect::where('connect_id', $connect_id)->where('item_id',$item_id)->where('item_type', $item_type)->whereIn('connect_type',[1,2,3])->update(['connect_status'=> 0 ]);
                    if($connect_status == 1){

                        $connect_id = (bd_item_connect::where('connect_id', $connect_id)->where('item_id',$item_id)->where('item_type', $item_type)->whereIn('connect_type',[1,2,3])->value('id_connect'));
                            bd_item_connect::where('id_connect', $connect_id)->update(['connect_status'=> 1]);
                    }


                }

            }
                return;

    }


    public static function item_image($image_list)
    {


        $link= image_url().'deals/small/';
        // $image_list= !empty($value)?json_decode($value,true):[];
        $list = [];

        if(!empty($image_list)){
            foreach( $image_list as $image){
                if(!empty($image['image_id'])){

                    $image_id = !empty($image['image_id'])?$image['image_id']:'default.png' ;
                    $list[] =['image_tag'=> $image['tag_name'],'image_url'=>$link.$image_id ];
                }

            }
            $images =  !empty($list)?$list : [['image_tag'=> 'unknown','image_url'=>$link.'default.png'] ];

            return $images;    // check for slug and send one image if there is lsug

        }
        return [['image_tag'=> 'unknown','image_url'=>$link.'default.png'] ];
    }



    // comment count
    public static function comment_count($item_id){
        $item_comment_count = bd_item_comment::where('item_id',$item_id)->whereIn('moderation_status', [1,2,3])->count();
        bd_item_deal::where('id_item',$item_id)->update(['count_item_comment' => $item_comment_count]);
        bd_item_discussion::where('id_item',$item_id)->update(['count_item_comment' => $item_comment_count]);
    }

    public static function random_string(){
        $length = 10;
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;

    }

    public static function update_encoded_json ($previous_data, $new_data){
            if(is_string($previous_data))  $list = json_decode($previous_data,true);

            $list = $list??[];
            $list[] = $new_data;

            $list = json_encode($list, JSON_PRETTY_PRINT);

                return $list;
    }

    public static function send_email($content){

            if(!empty($content)){

                    sqs_service::job([
                        'type'=>'email',
                        'operation'=>'general_message',
                        'content'=>["username"=>$content['user_handle'],
                                    "page_type"=>$content['page_name'],
                                    "button_name"=>'',
                                    'subject'=>$content['subject'],
                                    'header'=>$content['header'],
                                ],
                        'link'=>'',
                        'email'=>$content['user_email']]);


            }

            return;
    }

    public static function get_rate($item_group, $item_rate, $item_user_id){


        //  $list_group=json_decode( $item['item_group'],true);

        if(!is_array($item_group)){

            $item_group =json_decode($item_group,true);
        }

        $groups = self::filter_group($item_group);



        $rate_brand = !empty($groups['brands'][0])?bd_group::where('id_group',$groups['brands'][0])->value('group_rate'):10.00;
        $rate_brand =  $rate_brand ==0?10:$rate_brand;

        $rate_store = !empty($groups['stores'][0])?bd_group::where('id_group',$groups['stores'][0])->value('group_rate'):10.00;
        $rate_store = $rate_store==0?10:$rate_store;

        $rate_user = !empty($item_user_id)? MMUser::where('id_user',$item_user_id)->value('user_rate'):"";

        $rate_item = $item_rate??10;           //source could be item_rate or item_rate_main
        $rate_item = $rate_item==0?10:$rate_item;
        $rate_overall = ($rate_item + $rate_brand + $rate_store + $rate_user)/ 4;

                    $item_rate_overall = round($rate_overall,2)??10;
                    $rate_item = (float)(number_format($rate_item, 2))??10;
                    $rate_brand = round($rate_brand,2)??10;
                    $rate_store = round($rate_store,2)??10;
                    $rate_user = round($rate_user,2)??10;

        return ['item_rate_overall' => $item_rate_overall,
                'rate_item'=>$rate_item,
                 'rate_brand'=>$rate_brand,
                  'rate_store'=>$rate_store,
                  'rate_user'=>$rate_user];
    }

//return overall discussion rate
    public static function get_discussion_rate($item_rate, $item_user_id, $all=null){

        $rate_user = MMUser::where('id_user',$item_user_id)->value('user_rate');
        $rate_item = $item_rate ==0?10:$item_rate;
        $rate_overall = ($rate_user + $rate_item)/2;
        $rate_overall = round($rate_overall,2);


        if(!empty($all)){

            return ['item_rate_overall' => $rate_overall,
                'rate_item'=>$rate_item,
                 'rate_brand'=>10,
                  'rate_store'=>10,
                  'rate_user'=>$rate_user];

        }
        return ['item_rate_overall' => $rate_overall,
                'rate_item'=>$rate_item,
                'rate_user'=>$rate_user];

    }


   //Rebuild coupon item when user logs in

   public static function rebuild_coupon_item($user_id){

    $order_id = bd_order::where('user_id', $user_id)->where('order_status_payment',3)->where('order_status',1)->value('id_order');


      if(!empty($order_id))  sqs_service::job(['type'=>'rebuild_coupon_item', 'user_id'=>$user_id, 'order_id'=>$order_id]);

    return;
   }





   public static function move_image_toS3($today_req_content){

    // if(empty($todays_req_content)) {
    //     $today_req_content = bd_log_connect::where('request_type', 2)->value('content');
    //     $today_req_content = \json_decode($today_req_content, true);
    // }

    //$today_req_content = json_encode($today_req_content, JSON_PRETTY_PRINT);
    //file_put_contents('today_req_content.txt',$today_req_content.PHP_EOL , FILE_APPEND | LOCK_EX);

    // $today_req_content = bd_log_connect::where('created_at', '>=', DB::raw('CURDATE()'))->where('request_type', 2)->value('content');

    if (!file_exists('/tmp/tmpfile'))  mkdir('/tmp/tmpfile');

    $s3_folder = config('betterdeal.connect_url')=="https://stage.connect.betterdeal.com"?'logs/bd_stage/':'logs/bd_beta/';

    $note_path = '/tmp/tmpfile/todays_request.txt';
    $s3_key_path = $s3_folder.'todays_request.txt';

    file_put_contents($note_path,$today_req_content.PHP_EOL);

    //delete note first
    self::delete_image_fromS3( $s3_key_path);

    try{
        $response =   \App\Services\S3Handler::connect()->putObject([
                        'Bucket' => config('betterdeal.bucket_url'),
                        'Key' =>$s3_key_path ,
                        'ContentType' => 'text/json',
                        'ACL' => 'public-read',
                        'SourceFile' => $note_path,
                        'Metadata'   => array(
                            'Content_Type' => 'text/json',
                            'Cache_control' => 'max-age=31536000'
                                 )
                        ]);


                return $response;

        } catch(S3Exception $e){
            var_dump($e->getMessage());
        return $e->getMessage();
        } catch (Exception $e) {
            var_dump($e->getMessage());
        return $e->getMessage();
        }


}


//delete image form s3

        public static function delete_image_fromS3( $s3_key_path){

            try{
                $response =   \App\Services\S3Handler::connect()->deleteObject([
                            'Bucket' => config('betterdeal.bucket_url'),
                            'Key'    =>  $s3_key_path,
                        ]);

            return $response;

                } catch(S3Exception $e){
                    var_dump($e->getMessage());
                    return $e->getMessage();

                } catch (Exception $e) {
                    var_dump($e->getMessage());
                    return $e->getMessage();
                }


        }


        public static function  initialize_store_info($user_id){


                        $store_id = \App\Models\MMUser::where('id_user',$user_id)->value('store_id');


                        //resetting store url,store_id and store_name when we know it is admin
                        $store_name = store::where('id_store', $store_id)->value('store_name');
                        $store_version =  store::where('id_store', $store_id)->value('version');
                        $host_domain = Domain::where('store_id', $store_id)->orderBy('domain_default', 'desc')->value('domain_host');
                      //  $store_url = $host_domain == 'localhost'?config('betterdeal.public_url'):"https://".$host_domain;
                        $store_url = "https://".$host_domain;

                        Session::forget('process_store_id');
                        Session::forget('process_store_name');
                        Session::forget('process_store_url');
                        Session::forget('process_store_version');

                        Session::flash('process_store_id', $store_id);
                        Session::flash('process_store_url', $store_url);
                        Session::flash('process_store_name', $store_name);
                        Session::flash('process_store_version', $store_version);

                        return ['store_id'=>$store_id, 'store_url'=>$store_url, 'store_name'=>$store_name, 'store_version' => $store_version];
        }


        public static function store_admin($user_email){

            if(MMUser::where('user_role_type',3)->where('user_email', $request->user_email)->exists()){
                $user_id = MMUser::where('user_role_type',3)->where('user_email',$request->user_email)->value('id_user');
                $store = Helper::initialize_store_info($user_id);

            }

        }


        //check user is on this store

        public static function  check_email_on_store($user_email){

            $first_store_id = session()->get('process_store_id')??request()->process_store_id;
            $first_store_name = session()->get('process_store_name')??request()->process_store_name;
//initialize session with user store_id
             if(MMUser::where('user_role_type',3)->where('user_email', $user_email)->exists()){
            $user_id = MMUser::where('user_role_type',3)->where('user_email',$user_email)->value('id_user');
            $store = Helper::initialize_store_info($user_id);

        }

                    $user_store_id = session()->get('process_store_id')??request()->process_store_id;
                    $user_store_name = session()->get('process_store_name')??request()->process_store_name;


            //check if it is admin domain or web page
            if(MMUser::where('store_admin_type', 1)->where('store_id',$first_store_id)->exists()) return true;

            //check if user store and the environment  store  he is navigating are the same
            if($first_store_id !=  $user_store_id ) return false;


            if(!MMUser::where('store_id', $user_store_id)->where('user_email', $user_email)->exists()) return false;

            return true;
        }




        public  static function validate_store_request($model, $column_title, $value){

            $store_id = session()->get('process_store_id')??request()->process_store_id;
            $store_name = session()->get('process_store_name')??request()->process_store_name;

            // $item_id =  request()->route()->parameters()['id']??'';
            // $item_slug =  request()->route()->parameters()['slug']??'';

            // $search_column = !empty($item_id)?'id_item':(!empty($item_slug)?'item_slug':'');
            // $search_item = !empty($item_id)?$item_id:(!empty($item_slug)?$item_slug:'');


            if(!empty($value) &&!$model::where($column_title, $value)->where('store_id', $store_id)->exists()) return response()->json(['errors'=>['store'=>["$column_title supplied never exists for this store on store $store_name"]]], 422);
            return false;
        }









}

