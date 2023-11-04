<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

use App\Models\User;
use App\Repositories\RateRepository;

class RateRepositoryTest extends TestCase
{
    public function testFetchTodaysRate()
    {

        // Create a user and any necessary related data for testing
        $user = User::factory()->create();
       // dump($);
        // You may need to adjust the above factory according to your User model

        // Instantiate the RateRepository
        $rateRepository = new RateRepository();

        // Call the function and get the result
        $rate = $rateRepository->fetchTodaysRate();
        // Assert that the rate is not null or perform any other relevant assertions
        $this->assertNotNull($rate);
    }
}

