<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Bank;
use App\Models\Currency;
use App\Models\Rate;
use App\Models\User;
use Illuminate\Database\Seeder;


class BankSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void

    {
        Bank::truncate();

        Bank::factory(10)->create();

    }
}
