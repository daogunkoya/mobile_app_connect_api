<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Bank;
use App\Models\Currency;
use App\Models\Domain;
use App\Models\Rate;
use App\Models\Receiver;
use App\Models\Sender;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $userId = User::factory()->create()->id_user;

//        // Truncate the tables first
        User::truncate();
        Store::truncate();
        Receiver::truncate();
        Sender::truncate();
        Rate::truncate();
        Bank::truncate();
        Domain::truncate();
        Currency::truncate();

        Currency::factory(10)->create();
        Currency::factory()->create([
            'default_currency' => '1',
            'currency_code' => 'UK-NG',
            'currency_origin' => 'United Kingdom',
             'currency_destination' => 'Nigeria'
        ]);

        User::factory(10)->create();

        Store::factory(10)->create();
        Store::latest()->first()->update(['id_store' => '2bda0c37-4eac-44e5-a014-6c029d76dc62']);

       // Receiver::factory(10)->create(['user_id' => $userId]);
        Receiver::factory(10)->create();
        Sender::factory(10)->create(['user_id' => $userId]);
        Rate::factory(10)->create();
        Bank::factory(10)->create();
        Domain::factory(10)->create();
        Domain::factory(10)->create([]);

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
