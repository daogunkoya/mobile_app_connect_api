<?php

// database/factories/SenderFactory.php

namespace Database\Factories;

use App\Models\Sender;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Rate;
use App\Models\User;
class RateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'store_id' => $this->faker->randomNumber(),
            'main_rate'=> $this->faker->randomNumber(),
            'user_id' => User::factory()->create()->id_user,
            'currency_id' => $this->faker->randomDigit(),
            'bou_rate'=> $this->faker->randomNumber(),
            'sold_rate'=> $this->faker->randomNumber(),
            'moderation_status' =>1,
            'rate_status'=>1,
            // Add other required fields and their fake data
        ];
    }
}
