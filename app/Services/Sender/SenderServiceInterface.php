<?php

namespace App\Services\Sender;

use App\Models\sender;
use Illuminate\Support\Facades\DB;
use App\Services\Helper;
use App\Models\mm_log_device;
use Illuminate\Support\Facades\Http;
use App\Models\mm_user;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Repositories\SenderRepository;

interface SenderServiceInterface
{


    public function fetchSenders($input):array;


    //create new customer
    public function createSender($input, $user_id):string;



    //update customer
    public function updateSender($input, $user_id, $sender_id):bool;


    public function deleteSender($senderId):bool;
}
