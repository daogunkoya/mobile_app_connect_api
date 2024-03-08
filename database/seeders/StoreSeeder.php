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

             Store::factory()->create();
            // Store::factory()->create(['id_store' => '2bda0c37-4eac-44e5-a014-6c029d76dc62']);

     }
}
