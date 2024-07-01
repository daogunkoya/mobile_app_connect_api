<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Currency;
use App\Models\Rate;
use App\Models\User;
use Illuminate\Database\Seeder;


class CurrencySeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void

    {
        Currency::truncate();

        $userId = User::factory()->create()->id_user;
        $currencyId = Currency::factory()->create()->id_currency;

        Currency::factory(10)->create();

    }
}
