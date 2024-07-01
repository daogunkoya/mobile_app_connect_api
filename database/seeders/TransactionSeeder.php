<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Currency;
use App\Models\Rate;
use App\Models\Sender;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;


class TransactionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void

    {
        Transaction::truncate();


        Transaction::factory(10)->create();

    }
}
