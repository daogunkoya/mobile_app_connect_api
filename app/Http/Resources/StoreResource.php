<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'store_id' => $this->storeId,
            'store_name' => $this->storeName,
            'store_url' => $this->storeUrl,
            'store_phone' => $this->storePhone,
            'store_email' => $this->storeEmail,
            'store_address' => $this->storeAddress,
            'store_city' => $this->storeCity,
            'store_postcode' => $this->storePostcode,
            'store_country' => $this->storeCountry,
            'store_business_name' => $this->storeBusinessName,
            'store_slogan' => $this->storeSlogan,
            'store_mobile' => $this->storeMobile,
            'enable_sms' => $this->enableSms,
            'enable_credit' => $this->enableCredit,
            'enable_multiple_receipt' => $this->enableMultipleReceipt,
            'version' => $this->version,
        ];
    }
}
