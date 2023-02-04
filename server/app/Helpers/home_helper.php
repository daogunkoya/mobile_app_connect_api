<?php

use App\Models\bd_content;
use App\Models\bd_group;
use App\Models\mm_user;
use App\Models\bd_item_comment;
use App\Models\bd_item_connect;
use App\Models\bd_item_deal;
use App\Models\bd_item_discussion;
use App\Services\Helper;
use App\Services\Comments\comments_service;
use App\Services\Votes\vote_service;
use App\Services\Deals\deals_service;
use Illuminate\Support\Facades\DB;
use App\Services\Connections\connections_service;
//

 function fetch_content_page($request,$content){
     
    $request =$request??null;

    switch($content['content_type']){
        case 1:
            $user_saved = fetch_user_saved_item($request,$content); 
            
            $fetched_user_saved = !empty($user_saved)? 
                    ['item_type'=>$content['item_type'],'item_count'=>$user_saved['item_count'], 'item'=>$user_saved['item'] ] : [];
            
               
            return ['content_id' => $content['id_content'],
                    'content_title' => $content['content_title'],
                    'content_slug' => $content['content_slug'],
                    'content_type' => $content['content_type'], 
                    'content_user_slug' => Helper::auth_token()['user_handle']??'',
                    'content_item' => $fetched_user_saved ];
       
            break;

        case 3:
            $user_alert = fetch_user_alert($request,$content);
            $fetched_user_alert = !empty($user_alert)?
                        ['item_type'=>$content['item_type'], 'item_count'=>$user_alert['item_count'], 'item'=>$user_alert['item'] ] : [];
            
            return        [
                            'content_id' => $content['id_content'],
                            'content_title' => $content['content_title'],
                            'content_slug' => $content['content_slug'],
                             'content_type' => $content['content_type'],
                            'content_user_slug' => Helper::auth_token()['user_handle']??'',
                            'content_item' => $fetched_user_alert ];            
                            
        break;

        case 4:
            $fetched_custom = fetch_custom_categories($request,$content);

            $results = [
                            'content_id' => $content['id_content'],
                            'content_title' => $content['content_title'],
                            'content_slug' => $content['content_slug'],
                            'content_type' => $content['content_type'],
                            'content_item' => $fetched_custom ];
            
                            return $results;
       
        break;

        case 5:
            if(isset($request['query']['content_id']) && $content['id_content'] ==  $request['query']['content_id']){
        
                $row_no =isset($request['cursor'])?
                            bd_group::where('id_group',$request['cursor'])->value('row_number') : 0;
                $limit = $request['limit']??10;
        
                //var_dump($row_no);
        
        }else{$row_no = 0; $limit = 10; }
        
                if(!empty($content) && isset($content['item_type'])){
                    $groups = bd_group::
                    join('bd_item_connect', 'bd_group.id_group', '=', 'bd_item_connect.connect_id')
                    ->where('bd_group.group_type', 2)
                    ->where('bd_group.row_number', '>',$row_no)
                    ->where('bd_item_connect.connect_type', 1)
                    ->where('bd_item_connect.item_type', $content['item_type'])
                    ->select('bd_group.id_group as group_id','bd_group.group_slug','bd_group.group_title', DB::raw('COUNT(bd_item_connect.connect_id) as group_count'))
                    ->limit($limit)
                    ->groupBy('bd_group.group_slug','bd_group.group_title','bd_group.id_group','bd_group.row_number')
                    ->orderBy('bd_group.row_number', 'ASC')
                    ->orderBy('group_count', 'DESC')
                    ->get();
        
                    $group_count = bd_group::
                    join('bd_item_connect', 'bd_group.id_group', '=', 'bd_item_connect.connect_id')
                    ->where('bd_group.group_type', 2)
                    ->where('bd_item_connect.connect_type', 1)
                    ->where('bd_item_connect.item_type', $content['item_type'])
                    ->select('bd_group.group_slug','bd_group.group_title', DB::raw('COUNT(bd_item_connect.connect_id) as group_count'))
                    ->groupBy('bd_group.group_slug','bd_group.group_title')
                    ->orderBy('group_count', 'DESC')
                    ->get();
             
                     $fetched_topcategories =  !empty($groups)?['item_count'=>count($group_count->toArray()), 'item'=>$groups->toArray()]:[];     
                }
               // return []; 

            $results = [
                            'content_id' => $content['id_content'],
                            'content_title' => $content['content_title'],
                            'content_type' => $content['content_type'],
                            'content_slug' => $content['content_slug'],
                            'content_item' => ['item_type'=>$content['item_type'],'item_count'=>$fetched_topcategories['item_count'],'item'=>$fetched_topcategories['item'] ] ];

                            return $results;
       
        break;

        default:
        return [];
    }

}


 function fetch_custom_categories($request, $content){

    if(isset($request['query']['content_id']) && $content['id_content'] ==  $request['query']['content_id']){
        
            $connect_row =isset($request['cursor'])?
                        bd_item_connect::where('item_id',$request['cursor'])->where('connect_id', $content['list_group'])->value('row_number') : 0;
            $limit = $request['limit']??10;
    
    }else{$connect_row = 0; $limit = 10; }
    

        if(!empty($content['item_type'])) {
            switch($content['item_type']){
                case 1:
                   return  fetch_custom_deals_byCategory($content,$connect_row,$limit);
                     break;
            
                case 2:
                       return  fetch_custom_discussionss_byCategory($content,$connect_row,$limit);
                         break;
            }

        }

        return [];

}


 function fetch_topCategories($request,$content){
         
    if(isset($request['query']['content_id']) && $content['id_content'] ==  $request['query']['content_id']){
        
        $row_no =isset($request['cursor'])?
                    bd_group::where('id_group',$request['cursor'])->value('row_number') : 0;
        $limit = $request['limit']??10;

        //var_dump($row_no);

}else{$row_no = 0; $limit = 10; }

        if(!empty($content) && isset($content['item_type'])){
            $groups = bd_group::
            join('bd_item_connect', 'bd_group.id_group', '=', 'bd_item_connect.connect_id')
            ->where('bd_group.group_type', 2)
            ->where('bd_group.row_number', '>',$row_no)
            ->where('bd_item_connect.connect_type', 1)
            ->where('bd_item_connect.item_type', $content['item_type'])
            ->select('bd_group.id_group as group_id','bd_group.group_slug','bd_group.group_title', DB::raw('COUNT(bd_item_connect.connect_id) as group_count'))
            ->limit($limit)
            ->groupBy('bd_group.group_slug','bd_group.group_title','bd_group.id_group','bd_group.row_number')
            ->orderBy('bd_group.row_number', 'ASC')
            ->orderBy('group_count', 'DESC')
            ->get();

            $group_count = bd_group::
            join('bd_item_connect', 'bd_group.id_group', '=', 'bd_item_connect.connect_id')
            ->where('bd_group.group_type', 2)
            ->where('bd_item_connect.connect_type', 1)
            ->where('bd_item_connect.item_type', $content['item_type'])
            ->select('bd_group.group_slug','bd_group.group_title', DB::raw('COUNT(bd_item_connect.connect_id) as group_count'))
            ->groupBy('bd_group.group_slug','bd_group.group_title')
            ->orderBy('group_count', 'DESC')
            ->get();
     
             return !empty($groups)?['item_count'=>count($group_count->toArray()), 'item'=>$groups->toArray()]:[];     
        }
        return [];    

}


 function fetch_user_saved_item($request, $content){
    $user_auth = Helper::auth_token();
  
    if(!empty($user_auth)) {
   
    //pagination
        if(isset($request['query']['content_id']) && $content['id_content'] ==  $request['query']['content_id']){
            
            $cursor =$request['cursor']??0;
            $limit = $request['limit']??10;
    
            }else{$cursor = 0; $limit = 10; }


        $user_id =  Helper::auth_token()['id_user'];
       // var_dump($user_id);
        switch($content['item_type']){

            case 1:
                
                return connections_service::user_saved_deals($user_id,  $cursor, $limit);
            break;

            case 2:
                return connections_service::user_saved_discussions($user_id,  $cursor, $limit);
            break;
        }

  }
  return [];

}


 function fetch_user_alert($request,$content){

    if(isset(Helper::auth_token()['id_user'])) {
          $user_id =  Helper::auth_token()['id_user'];
        
          //pagination
        if(isset($request['query']['content_id']) && $content['id_content'] ==  $request['query']['content_id']){
    
            $cursor =$request['cursor']??0;
            $limit = $request['limit']??10;
    
            }else{$cursor = 0; $limit = 10; }
       
          switch($content['item_type']){

              case 1:
                  
                  return connections_service::user_alerts_deals($user_id,  $cursor, 10);
              break;

              case 2:
                  return connections_service::user_alerts_discussions($user_id, $cursor, 10);
              break;
          }

    }
    return [];

  }








//customizing fetching for deals
  function fetch_custom_deals_byCategory($content,$connect_row,$limit){
    $order = $content['content_sort'] == 1?'ASC':'DESC';

    $count = bd_item_deal::join('bd_item_connect','bd_item_connect.item_id','=', 'bd_item_deal.id_item')
                                ->join('mm_user','mm_user.id_user','=','bd_item_deal.user_id' )
                                ->where('bd_item_connect.connect_id',$content['list_group'])
                                ->where('bd_item_connect.connect_type', 1)
                                ->where('mm_user.moderation_status', 1)
                                ->where('bd_item_connect.row_number','>',$connect_row??0)
                                ->count();

    $fetch_categories = bd_item_deal::join('bd_item_connect','bd_item_connect.item_id','=', 'bd_item_deal.id_item')
                                ->join('mm_user','mm_user.id_user','=','bd_item_deal.user_id' )
                                ->where('bd_item_connect.connect_id',$content['list_group'])
                                ->where('bd_item_connect.connect_type', 1)
                                ->where('mm_user.moderation_status', 1)
                                ->where('bd_item_connect.row_number','>',$connect_row??0)
                                ->select('bd_item_deal.created_at as time_ago','bd_item_deal.id_item AS item_id',
                                            'bd_item_deal.item_type','bd_item_deal.item_code','bd_item_deal.item_url','bd_item_deal.item_title',
                                            'bd_item_deal.item_slug',
                                            'bd_item_deal.list_image as item_image',  'bd_item_deal.item_price_sale',
                                            'bd_item_deal.item_price_compare','bd_item_deal.list_group as item_group',
                                            'bd_item_deal.item_rate','bd_item_deal.item_rate_overall'
                                            ,'bd_item_deal.user_id')
                                ->orderBy('bd_item_connect.row_number',$order)
                                ->limit($limit??6)
                                ->get();



                              
    $list = [];
  
    
    if(empty($fetch_categories)) return [];

    foreach ($fetch_categories as $category_deal) {     
       
             $list_deals[] = deal_fetch($category_deal->toArray(),$content['content_sort'], $content['list_group']);
        

    }
    //var_dump($id_group);
    $list['item_type'] = $content['item_type'];
    $list['item_count'] =$count;
    $list['item'] = $list_deals??[];


    return $list;

}




//customize fetch for discussions

 function fetch_custom_discussionss_byCategory($content,$connect_row,$limit){
   

    $order = $content['content_sort'] == 1?'ASC':'DESC';


                                $count = bd_item_discussion::join('bd_item_connect','bd_item_connect.item_id','=', 'bd_item_discussion.id_item')
                                ->join('mm_user','mm_user.id_user','=','bd_item_discussion.user_id' )
                                ->where('bd_item_connect.connect_id',$content['list_group'])
                                ->where('bd_item_connect.connect_type', 1)
                                ->where('mm_user.moderation_status', 1)
                                ->where('bd_item_connect.row_number','>',$connect_row??0)
                               ->count();

   $fetch_categories = bd_item_discussion::join('bd_item_connect','bd_item_connect.item_id','=', 'bd_item_discussion.id_item')
                                ->join('mm_user','mm_user.id_user','=','bd_item_discussion.user_id' )
                                ->where('bd_item_connect.connect_id',$content['list_group'])
                                ->where('bd_item_connect.connect_type', 1)
                                ->where('mm_user.moderation_status', 1)
                                ->where('bd_item_connect.row_number','>',$connect_row??0)
                                ->select('bd_item_discussion.created_at as time_ago','bd_item_discussion.id_item AS item_id','bd_item_discussion.item_title','bd_item_discussion.item_slug','bd_item_discussion.item_content',
                                'bd_item_discussion.list_image as item_image','bd_item_discussion.list_group as item_group', 'bd_item_discussion.item_rate','bd_item_discussion.item_rate_overall'
                                    ,'bd_item_discussion.user_id')
                               ->orderBy('bd_item_connect.row_number',$order)
                               ->limit($limit)
                               ->get();



                              
    $list = [];
  
    
    if(empty($fetch_categories)) return [];

    foreach ($fetch_categories as $category_discussion) {     
       
        $list_discussion[] = fetch_discussion($category_discussion->toArray(),$content['content_sort'], $content['list_group']);
        

    }










   

    $list['item_type'] = $content['item_type'];
    $list['item_count'] = $count;
    $list['item'] = $list_discussion??[];

    return $list;
}




//
  function deal_fetch($item,$sort, $category_id){
     
     // var_dump($item_id);
     if(!empty($item)){
          
        $link= image_url().'deals/small/';
        $price_difference = !empty($item['item_price_sale']) && !empty($item['item_price_compare'])?((float)$item['item_price_compare'] - (float)$item['item_price_sale'])/(float)$item['item_price_sale'] * 100:0;
        $item_user=  mm_user::where('id_user',$item['user_id'])->select('user_handle','list_image')->first();
        $user= !empty( $item_user)? $item_user->toArray(): [];
        
        $item['time_ago'] = \Carbon\Carbon::createFromTimeStamp(strtotime($item['time_ago']))->longAbsoluteDiffForHumans();
        $item['item_image'] = [ $item['item_image'][0] ];
        $item['item_price'] = ['price_sale'=>round($item['item_price_sale'],2),
                        'price_compare'=>round($item['item_price_compare'], 2),
                        'price_difference'=>round($price_difference,2)];
                        
        $item['item_user'] = !empty($item_user)? ['user_id'=> $item['user_id'],'user_handle'=> $user['user_handle'], 
                                    'user_image_url'=> image_url().'user/profile/small/'
                                    .(!empty($user['list_image'])?$user['list_image']:'default.png') ] : null;

      //  $list_group=json_decode( $item['item_group'],true);
        $list_group=$item['item_group'];
        $groups = deals_service::filter_group($list_group);
   
        $rate_brand = !empty($groups['brands'][0])?bd_group::where('id_group',$groups['brands'][0])->value('group_rate'):10.00;
        $rate_store = !empty($groups['stores'][0])?bd_group::where('id_group',$groups['stores'][0])->value('group_rate'):10.00;
                                   
         
        //$item['item_rate'] = !isset($item['item_rate']) || $item['item_rate']==0 ?10:$item['item_rate']; 
        $item['item_rate'] =[
            'item_rate_overall'=>round($item['item_rate_overall'],2),
             'rate_item'=> (float)(number_format($item['item_rate']==0?10:$item['item_rate'], 2)),
             'rate_brand'=>round($rate_brand,2),
             'rate_store'=>round($rate_store,2),
             'rate_user'=> !empty($item_user)? mm_user::where('id_user',$item['user_id'])->value('user_rate'):""
                ];


        
        
       //filter on category needed   
      
        if(!empty($category_id)){
            $new_list = [];
            foreach($list_group as $group) {
                if($group['group_type']== 3 || $group['group_type']== 4 || $group['group_id']== $category_id){
                    $new_list [] = $group;
                }
            } 
        }
            
        $item['item_group'] =$new_list??$list_group; 
                              
        unset($item['item_rate_overall']);  
        unset($item['user_id']);  
        unset($item['item_price_sale']); 
        unset($item['item_price_compare']);           

        return $item;

        }
        return [];
}











  function fetch_discussion($item,$sort,$category_id){
    if(!empty($item)){

                    $link= image_url().'discussions/small/';

                    $user= mm_user::where('id_user',$item['user_id'])->select('user_handle','list_image')->first();
                    $user= !empty($user)?$user->toArray():[];
                    
                    $item['time_ago'] = \Carbon\Carbon::createFromTimeStamp(strtotime($item['time_ago']))->longAbsoluteDiffForHumans();
                    $item['item_content'] = substr($item['item_content'],0, 200 );
                    $item['item_image'] = [ $item['item_image'][0] ];
                   
                   $comment_count = bd_item_comment::where('item_id', $item['item_id'])->where('item_type',2)->count();
                   $item ['item_comment'] = ['comment_count' =>   $comment_count ];

                    $item['item_user'] =  !empty($user)?['user_id'=> $item['user_id'],'user_handle'=> $user['user_handle'], 
                                                'user_image_url'=> image_url().'user/profile/small/'
                                                .(!empty($user['list_image'])?$user['list_image']:'default.png') ] : [];
                
                    $item['item_rate'] =!isset($item['item_rate']) || $item['item_rate']==0 ?10:$item['item_rate']; 
                   $list_group= $item['item_group'];
             
             
                   if(!empty($category_id)){
                          //filter on category needed     
                    $new_list = [];
                    foreach($list_group as $group) {
                        if($group['group_id']== $category_id){
                            $new_list [] = $group;
                        }
                    } 
                }    
            

                    $item['item_group'] =$new_list??$list_group; 
                         
                    
                    unset($item['user_id']);
                


             return $item;
                    
    }
    return [];
}