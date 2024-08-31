<?php

namespace App\DTO;

use App\Models\AcceptableIdentity;
use App\Models\Store;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


class StoreDto
{
    public function __construct(
        public string $storeId,
        public string $storeName,
        public ?string $storeBusinessName,
        public ?string $storePhone,
        public ?string $storeMobile,
        public ?string $storeSlogan,
        public ?string $storeEmail,
        public ?string $storeAddress,
        public ?string $storePostcode,
        public ?string $storeCity,
        public ?string $storeCountry,
        public ?string $storeUrl,
        public ?bool $enableSms,
        public ?bool $enableCredit,
        public ?bool $enableMultipleReceipt,
        public ?string $version

    )
    {
    }

    public static function fromEloquentModel(Store $store): self
    {
        return new self(
            $store->id_store,
            $store->store_name,
            $store->store_business_name,
            $store->store_phone,
            $store->store_mobile,
            $store->store_slogan,
            $store->store_email,
            $store->store_address,
            $store->store_postcode,
            $store->store_city,
            $store->store_country,
            $store->store_url,
            $store->enable_sms,
            $store->enable_credit,
            $store->enable_multiple_receipt,
            $store->version

        );
    }
   

    public static function fromEloquentModelCollection(LengthAwarePaginator $storeList): LengthAwarePaginator
    {

        return new LengthAwarePaginator(
            collect($storeList->items())->map(fn( Store $store) => self::fromEloquentModel($store)),
            $storeList->total(),
            $storeList->perPage(),
            $storeList->currentPage(),
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );


    }



}
