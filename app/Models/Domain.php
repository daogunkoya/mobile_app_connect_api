<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use DateTimeInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Domain extends Model
{
    use HasFactory;
            protected $table = "mm_domain";
            protected $primaryKey = 'id_domain';

            protected $fillable = [
            'store_id',
           // 'user_id',
            'domain_name',
            'domain_host',
            'domain_slug',
            'domain_default',
            'domain_verified',
            'domain_status',
            'moderation_status',
            'domain_local',
            'aws_ssl_arn',
            'aws_resource_record',
            'domain_ssl_verified',
            'domain_cname_verified'

            ];


                protected $keyType = 'string';

                public $incrementing = false;

            //date serialization undo
            protected function serializeDate(DateTimeInterface $date)
            {
                return $date->format('Y-m-d H:i:s');
            }


            public static function boot()
            {
                parent::boot();
                self::creating(function ($model) {
                    $model->id_domain = (string) Uuid::uuid4();


                    //domain host
                    $url = \parse_url($model->domain_name);
                    $domain_host = $url['host'] ?? '';
                    $model->domain_host = $domain_host;

                      // generate uniqu slug
                      $slug = str_slug($model->domain_name) . mt_rand(100, 1000);
                    while (bd_domain::where('domain_slug', $slug)->exists()) {
                        $slug = str_slug($model->domain_name) . mt_rand(100, 1000);
                    }
                      $model->domain_slug = $slug;
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

            public function getCreatedAtAttribute($value)
            {
                return \Carbon\Carbon::createFromTimeStamp(strtotime($value))->format('d/m/Y');
            }

            // public function getCreatedAtAttribute($value){
            //     return \Carbon\Carbon::createFromTimeStamp(strtotime($value))->format('d/m/Y');
            // }
}
