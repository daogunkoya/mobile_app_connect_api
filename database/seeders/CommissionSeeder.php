<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Commission;
use App\Models\Currency;
use App\Models\Rate;
use App\Models\User;
use Illuminate\Database\Seeder;


class CommissionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void

    {
        Rate::truncate();

        $userId = User::factory()->create()->id_user;

        Commission::factory()->create(
            [
                'start_from' => 101,
                'end_at' => 200,
                'value' => 10
            ]);
        Commission::factory()->create(
            [
                'start_from' => 201,
                'end_at' => 300,
                'value' => 12
            ]);
        Commission::factory()->create(
            [
                'start_from' => 301,
                'end_at' => 400,
                'value' => 15
            ]);
        Commission::factory()->create(
            [
                'start_from' => 401,
                'end_at' => 500,
                'value' => 20
            ]);
        Commission::factory()->create(
            [
                'start_from' => 501,
                'end_at' => 600,
                'value' => 25
            ]);
        Commission::factory()->create(
            [
                'start_from' => 601,
                'end_at' => 700,
                'value' => 25
            ]);
        Commission::factory()->create(
            [
                'start_from' => 701,
                'end_at' => 800,
                'value' => 30
            ]);
        Commission::factory()->create(
            [
                'start_from' => 801,
                'end_at' => 900,
                'value' => 35
            ]);
        Commission::factory()->create(
            [
                'start_from' => 901,
                'end_at' => 999,
                'value' => 40
            ]);
        Commission::factory()->create(
            [
                'start_from' => 1000,
                'end_at' => 1000,
                'value' => 50
            ]);

    }
}
