<?php

// database/factories/ReceiverFactory.php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\Receiver;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Bank;
use App\Models\Sender;
use App\Models\AcceptableIdentity;
use Illuminate\Support\Facades\Auth;

class ReceiverFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Receiver::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $userId =  User::factory()->create()->id_user;
        return [
//            'user_id' => $userId,
            'sender_id' => Sender::factory()->create()->id_sender,
            'store_id'=>'2bda0c37-4eac-44e5-a014-6c029d76dc62',
            'receiver_email' => $this->faker->unique()->safeEmail(),
            'receiver_fname' => $this->faker->firstName(),
            'receiver_lname' => $this->faker->lastName(),
            'receiver_mname' => $this->faker->firstName(),
//            'receiver_mobile' => $this->faker->phoneNumber(),
            'receiver_phone' => $this->faker->phoneNumber(),
//            'receiver_postcode' => $this->faker->postcode(),
            'receiver_title' => $this->faker->title(),
           'receiver_address' => $this->faker->address(),
            'transfer_type' => "bank",
            'account_number' =>$this->faker->randomNumber(),
            'currency_id' => Currency::factory()->create()->id_currency,
            'bank_id' => Bank::factory()->create()->id,
            'identity_type_id' => AcceptableIdentity::factory()->create()->id,
//            'photo_id' =>""
            // Add other required fields and their fake data
        ];
    }
}
