<?php

namespace Database\Factories;

use App\Models\Commission;
use App\Models\Currency;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class CommissionFactory extends Factory
{
    protected  $model = Commission::class;
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
            'user_id' => '',
            'start_from' => 1,
            'end_at' => 100,
            'value' => 5,
            'agent_quota' =>50,
            'currency_id' => '',
            'moderation_status' => 1,
            'commission_status' => 1
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
