<?php

// database/factories/SenderFactory.php

namespace Database\Factories;

use App\Models\Currency;
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
            'store_id' => '2bda0c37-4eac-44e5-a014-6c029d76dc62',
            'main_rate'=>  $this->faker->randomElement([500, 400, 900, 670, 994]),
            'user_id' => User::latest()->first()->id_user,
            'currency_id' =>  '40f85a89-a1fe-42ec-8983-457701ab3bf5',
            'bou_rate'=> $this->faker->randomElement([500, 400, 900, 670, 234]),
            'sold_rate'=>$this->faker->randomElement([500, 400, 900, 670, 234]),
            'moderation_status' =>1,
            'rate_status'=>1,
            // Add other required fields and their fake data
        ];
    }
}
