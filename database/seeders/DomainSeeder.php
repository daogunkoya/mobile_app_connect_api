<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Domain;

class DomainSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void

     {
         for ($i = 0; $i < 5; $i++) {
             Domain::factory()->create();
         }
     }
}
