<?php

use App\Models\Commission;
use App\Models\Domain;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

beforeEach(function () {
    uses(RefreshDatabase::class);

    // Create a Passport-authenticated user
    $user = User::factory()->create();
    Passport::actingAs($user);
});

it('can access the index method of ComissionController', function () {

//    $user = User::factory()->create();
//    Passport::actingAs($user);

    $commission = Commission::factory()->create();

    $domains = Domain::factory()->create();
    $response = $this->get(route('commissions.index'));

    $response->assertStatus(200);
    // Add more assertions as needed
});
