<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'uri',
        'method',
        'request_body',
        'response_body',
        'status_code',
        'request_ip',
        'request_url',
        'request_body',
        'response_data  '
    ];
}
