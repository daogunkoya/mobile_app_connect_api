<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Enum\UserRoleType;
use App\Enum\UserStatus;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        $userRole = $this->userRoleType === UserRoleType::ADMIN ;
        return [
            'user_id' => $this->userId,
            'user_title' => $this->title,
            'user_name' => "$this->firstName $this->lastName",
            'user_fname' => $this->firstName,
            'user_lname' => $this->lastName,
            'user_mname' => $this->middleName,
            'user_dob' => $this->dob,
            'user_email' => $this->email,
            'user_email_verified_at' => $this->email_verified_at,
            'user_phone' => $this->phone,
            'user_address' => $this->address,
            'user_postcode' => $this->postcode,
            'user_metadata' => $this->metaData,
            'user_currency' => new UserCurrencyResource($this->userCurrency),
            'user_identity' => new UserDocumentResource($this->userDocument),
            'user_role' => ucfirst($this->userRoleType->label()),
            'user_status' => ucfirst($this->userStatus->label()),
            'transaction_count' => $this->transactionCount,
            'receiver_count' => $this->receiverCount,
            'sender_count' => $this->senderCount,
            'outstanding' => $this->outstanding,
            
        ];
    }
}
