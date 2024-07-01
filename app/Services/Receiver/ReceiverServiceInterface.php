<?php

namespace App\Services\Receiver;

use App\Models\Receiver;
use App\Repositories\ReceiverRepository;
use Illuminate\Pagination\LengthAwarePaginator;

interface ReceiverServiceInterface
{

    public function fetchReceiver($sender):LengthAwarePaginator;

    public function createReceiver($input, $sender_id): string;

    public function updateReceiver($input, $receiver_id): bool;

    public function deleteReceiver($receiverId): bool;

    public function showReceiver($receiverId): ?Receiver;

}
