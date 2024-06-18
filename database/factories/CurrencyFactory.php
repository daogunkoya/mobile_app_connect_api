<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class CurrencyFactory extends Factory
{
    protected  $model = Currency::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           // 'id'=> (string) Uuid::uuid4(),
            'store_id'=>'2bda0c37-4eac-44e5-a014-6c029d76dc62',
            'user_id' => Str::uuid(),
            'currency_type' => 1,
            'currency_country' => fake()->randomElement(['Nigeria', 'Ghana', 'Benin', 'United-Kingdom']),
             'currency_symbol' => fake()->randomElement(['NG', 'GH', 'BN', 'UK']),
            // 'currency_destination'=> fake()->randomElement(['Nigeria', 'Ghana', 'Benin']),
            // 'currency_destination_symbol' => fake()->randomElement(['NG', 'GH', 'BN']),
            // 'currency_code' => 'UK-NG',
            'default_currency' => 0,
            'income_category'=> 1,
            'currency_status' => 1,
            'moderation_status' => 1
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
