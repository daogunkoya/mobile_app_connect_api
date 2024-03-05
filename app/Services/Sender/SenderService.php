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
use App\Services\Sender\SenderServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;


class SenderService implements SenderServiceInterface
{

    public function __construct(protected SenderRepository  $senderRepository){

}
    //fetch customer
    public function fetchSenders($input):LengthAwarePaginator
    {

        return $this->senderRepository->fetchSenders($input);
    }


    //create new customer
    public function createSender($input, $user_id):string
    {

      return $this->senderRepository->createSender($input, $user_id);
    }


    //update customer
    public function updateSender($input, $user_id, $sender_id):bool
    {
        return $this->senderRepository->updateSender($input, $user_id, $sender_id);
    }

    public function deleteSender($senderId):bool{
        return $this->senderRepository->deleteSender($senderId);
    }

    public function showSender($senderId):object{
        return $this->senderRepository->showSender($senderId);
    }
}
