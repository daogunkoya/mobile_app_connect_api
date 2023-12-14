<?php

namespace Tests\Unit;

use App\Models\Rate;
use App\Models\User;
use App\Repositories\RateRepository;
use Tests\TestCase;

class RateRepositoryTest extends TestCase
{
    public function testFetchTodaysRate()
    {
        // Create a rate using the factory
        $rate = Rate::factory()->create();
        $this->assertNotNull(Rate::find($rate->id_rate));
    }
}

