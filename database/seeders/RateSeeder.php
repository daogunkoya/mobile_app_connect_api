<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Currency;
use App\Models\Rate;
use App\Models\User;
use Illuminate\Database\Seeder;


class RateSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void

    {
        Rate::truncate();

        $userId = User::factory()->create()->id_user;
        $currencyId = Currency::factory()->create()->id_currency;

        Rate::factory()->create(['user_id' =>'']);
        Rate::factory(10)->create(['user_id' => $userId, 'currency_id' => $currencyId]);

    }
}
