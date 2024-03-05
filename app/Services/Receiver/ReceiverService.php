<?php

namespace App\Services\Receiver;

use App\Models\Receiver;
use App\Repositories\ReceiverRepository;
use App\Services\Receiver\ReceiverServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ReceiverService implements ReceiverServiceInterface
{

    public function __construct(public ReceiverRepository $receiverRepository
    )
    {

    }

    public function fetchReceiver($sender): LengthAwarePaginator
    {
        return $this->receiverRepository->fetchReceiver($sender);
    }


    public function createReceiver($input, $sender_id): string
    {
        return $this->receiverRepository->createReceiver($input, $sender_id);

    }


    public function updateReceiver($input, $receiver_id): bool
    {

        return $this->receiverRepository->updateReceiver($input, $receiver_id);
    }

    public function deleteReceiver($receiverId): bool
    {

        return $this->receiverRepository->deleteReceiver($receiverId);
    }

    public function showReceiver($receiverId): ?Receiver
    {

        return $this->receiverRepository->showReceiver($receiverId);

    }
}
