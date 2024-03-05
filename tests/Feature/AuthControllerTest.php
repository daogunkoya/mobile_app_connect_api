<?php

namespace Tests\Feature;

use App\Models\Domain;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Laravel\Passport\Passport;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        //$this->artisan('seed:db');

        // Run any other setup commands
        //  exec('php artisan passport:install --uuids');

        parent::setUp();
//        $this->faker = Faker::create();

        // Authenticate a user using Passport actingAs()
//        $user = User::factory()->create();
//        Passport::actingAs($user);

        // Set default headers for API requests
        $this->withHeaders([
            'Accept' => 'application/json',
            // 'process_store_id' => '2bda0c37-4eac-44e5-a014-6c029d76dc62'
            // Add more headers if needed
        ]);


        $domainFactory = Domain::factory(5)->create();
        $this->artisan('passport:install');
    }

    /**
     * A basic feature test example.
     */
    public function test_user_can_register(): void
    {

        $this->artisan('passport:install');


        $userFactory = User::factory()->make();

        $response = $this->postJson('/v1/users', [
            'first_name' => $userFactory->first_name,
            'last_name' => $userFactory->last_name,
            'email' => $userFactory->email,
            'password' => 'amtestingthis4real',
            'password_confirmation' => 'amtestingthis4real',
        ]);

        // $response->dd();
        $response->assertCreated();

        $user = User::latest()->first();

        $this->assertEquals($userFactory->email, $user->email);
        $this->assertEquals($userFactory->first_name, $user->first_name);
    }

    #[Test]
    public function test_user_can_login(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('v1/users/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['data' => ['access_token', 'user_id']]);

        $responseData = $response->json();
        $this->assertEquals($user->id_user, $responseData['data']['user_id']);
    }

}
