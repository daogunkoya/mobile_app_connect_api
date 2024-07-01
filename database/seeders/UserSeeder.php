<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Currency;
use App\Models\Rate;
use App\Models\Sender;
use App\Models\User;
use Illuminate\Database\Seeder;


class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void

    {
        User::truncate();


        User::factory(10)->create();
        User::factory()->create(['email' => 'boss.delia@example.org']);

    }
}
