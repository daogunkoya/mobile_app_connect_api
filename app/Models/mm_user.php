<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Ramsey\Uuid\Uuid;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class mm_user extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    //

    protected $table = "mm_user";
    protected $primaryKey = 'id_user';

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image_id',
        'store_id',
        'user_credit',
        'user_active_currency',
        'user_role_type',
        'user_name',
         'user_handle',
         'user_email',
         'user_password_hash',
         'user_password_salt',
         'user_access_id',
         'user_access_type',
         'user_last_active',
         'user_status',
         'count_item_comment',
         'count_item_deal',
         'count_item_discussion',
         'count_item_save',
         'count_item_report',
         'count_item_share',
         'count_item_cta',
         'count_item_vote',
         'count_item_vote_up',
         'user_rate',
         'user_count_vote_down',
         'user_count_item_connect',
         'record_count_update',
         'record_count_process',
         'record_note',
        'email',
        'user_alert_frequency',
        'user_alert_status',
        'user_email_status',
        'user_password_token',
        'list_access',
        'moderation_status'







    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
        'count_item_view','count_item_report', 'count_item_deal','count_item_discussion','count_item_comment'
    ];

    protected $keyType = 'string';

    public $incrementing = false;
    //protected $appends = ['user_image_url'];

    //date serialization undo
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id_user = (string) Uuid::uuid4();
            $model->user_status = 1;
            $model->user_email_status = 1;
        });
    }
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function getKeyType()
    {
        return 'string';
    }




    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    public function setPasswordAttribute($password)
    {
        if (!empty($password)) {
            $this->attributes['user_password_hash'] = bcrypt($password);
        }
    }

    public function getAuthPassword()
    {
        return $this->user_password_hash;
    }


    public function getNameAttribute()
    {
        return "{$this->user_name}";
    }



    public function getValueAttribute($value)
    {
        return strtoupper($value);
    }



// public function getTimeAgoAttribute($value){
//     return \Carbon\Carbon::createFromTimeStamp(strtotime($value))->longAbsoluteDiffForHumans();
// }

// public function getUserCountAttribute($value){
//     //var_dump($value);
//     return [
//         'count_rate' => mm_user::where('id_user', $value)->value('user_rate'),
//         'count_view' => mm_user::where('id_user', $value)->value('count_item_view')??0,
//         'count_deal'=> bd_item_deal::where('user_id', $value)->where('item_status', 1)->where('moderation_status',2)->count()??0,
//         'count_discussion'=>  mm_user::where('id_user', $value)->value('count_item_discussion')??0,
//         'count_comment' =>mm_user::where('id_user', $value)->value('count_item_comment')??0,
//         'count_report' => mm_user::where('id_user', $value)->value('count_item_report')??0
//     ];
// }


// public function getUserCountDealAttribute($value){
//     return   bd_item_deal::where('user_id', $value)->where('item_status', 1)->count()??0;
// }


// public function getUserCountDiscussionAttribute($value){
//     return  bd_item_discussion::where('user_id', $value)->where('item_status', 1)->count()??0;
// }


// public function getUserCountCommentAttribute($value){
//     return  bd_item_comment::where('user_id', $value)->where('comment_status', 1)->count()??0;
// }

// public function getUserGroupAttribute($value){
//     $groups = mm_user_connect::where('user_id', $value)->where('connect_status', 1)->pluck('connect_id');
//     $groups = !empty($groups)?$groups->toArray():[];
//     return  json_encode($groups);
// }


    public function getUserImageUrlAttribute($value)
    {

        $image_id = !empty($value) ? $value : 'default.png' ;

        return image_url() . 'user/profile/small/' . $image_id;
    }





//relationships

    public function deal()
    {
        return $this->hasMany('App\Models\bd_item_deal', 'user_id');
    }

    public function connects()
    {
        return $this->hasMany('App\Models\mm_user_connect');
    }

    public function mm_user_confirm()
    {
        return $this->hasOne('mm_user_confirm', 'user_email');
    }



// public function getAuthEmail()
// {
//     return $this->user_email;
// }
// public function username()
// {
//     return 'user_email';
// }

// public function getEmailAttribute()
//     {
//         return $this->user_email;
//     }
}
