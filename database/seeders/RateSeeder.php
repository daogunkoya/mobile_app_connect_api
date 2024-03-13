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

        $user = User::inRandomOrder()->first()??User::factory()->create();


        Rate::factory(3)->create(['user_id' =>'']);
        Rate::factory(7)->create(['user_id' => $user->id_user]);

    }
}
