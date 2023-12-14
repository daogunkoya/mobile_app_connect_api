<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Store;

class StoreSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void

     {
         for ($i = 0; $i < 5; $i++) {
             Store::factory()->create();
         }
     }
}
