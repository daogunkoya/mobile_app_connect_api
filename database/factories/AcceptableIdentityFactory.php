<?php

// database/factories/SenderFactory.php

namespace Database\Factories;

use App\Models\AcceptableIdentity;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
class AcceptableIdentityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AcceptableIdentity::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
           // 'user_id' => User::factory()->create()->id_user,
            'store_id' =>"2bda0c37-4eac-44e5-a014-6c029d76dc62",
            'name' => $this->faker->name(),


        ];
    }
}
