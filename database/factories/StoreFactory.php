<?php

// database/factories/SenderFactory.php

namespace Database\Factories;

use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;
class StoreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Store::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => "cfa2cf6-2606-4bc2-907b-0000ef0b87d6",
            'store_slug' => 'any-name',
            'store_admin_type' => "1",
            'moderation_status' => 1,
            'store_status' => 1,
            'store_user_email' => fake()->email,
            'store_name' => fake()->company(),
    
            'store_business_name' =>  fake()->company(),
            'store_business_type_id' =>  "test",
            'store_business_vat' =>  "test",
            'social_facebook' =>  "test",
            'social_twitter' =>  "test",
            'social_linkedin' =>  "test",
            'social_google' =>  "test",
            'social_instagram' => "test",
            'payment_status' => 1,
            'payment_url' =>  "test",
            'store_address' =>  "test",
            'store_city' =>  "test",
            'store_postcode' =>  "test",
            'store_country' =>  "test",
            'store_phone' =>  "test",
            'store_email' =>  "test",
            'enable_credit' =>  1,
            'enable_multiple_receipt' =>  1,
            'enable_sms' =>  1,


//            'stripe_count_onboard_refresh' =>  "test",
//            'stripe_count_onboard_return' =>  "test",
//            'stripe_account_id' =>  "test",
//            'stripe_onboard_complete' =>  "test",
            // Add other fields as needed
        ];
    }
}
