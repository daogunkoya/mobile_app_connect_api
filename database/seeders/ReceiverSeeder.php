<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Currency;
use App\Models\Rate;
use App\Models\Receiver;
use App\Models\User;
use Illuminate\Database\Seeder;


class ReceiverSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void

    {
        Receiver::truncate();

        Receiver::factory(10)->create();

    }
}
