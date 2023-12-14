<?php

// database/factories/SenderFactory.php

namespace Database\Factories;

use App\Models\Sender;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
class SenderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sender::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create()->id_user,
            'store_id'=>'2bda0c37-4eac-44e5-a014-6c029d76dc62',
            'sender_dob' => $this->faker->date(),
            'sender_email' => $this->faker->unique()->safeEmail(),
            'sender_fname' => $this->faker->firstName(),
            'sender_lname' => $this->faker->lastName(),
            'sender_mname' => $this->faker->firstName(),
            'sender_mobile' => $this->faker->phoneNumber(),
            'sender_phone' => $this->faker->phoneNumber(),
            'sender_postcode' => $this->faker->postcode(),
            'sender_title' => $this->faker->title(),
            'sender_address' => $this->faker->address(),
            'photo_id' =>""
            // Add other required fields and their fake data
        ];
    }
}
