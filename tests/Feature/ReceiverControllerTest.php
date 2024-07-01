<?php

namespace Tests\Feature;

use App\Models\AcceptableIdentity;
use App\Models\Bank;
use App\Models\Currency;
use JetBrains\PhpStorm\NoReturn;
use PHPStan\BetterReflection\Identifier\IdentifierType;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use App\Models\User;
use App\Models\Domain;
use App\Models\Store;
use App\Models\Receiver;
use App\Models\Sender;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\DomainSeeder;
use Database\Seeders\RateSeeder;
use  Faker\Generator;

class ReceiverControllerTest extends TestCase
{
    protected Generator $faker;

    use RefreshDatabase;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        // Seed the database with necessary data
        // Use Seeders to populate the tables
      //  Artisan::call('db:seed', ['--class' => 'DomainSeeder']);
       // Artisan::call('db:seed', ['--class' => 'StoreSeeder']);



//        $storeData = Store::Factory()->create();
//        $domainData = Store::Factory()->create();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();

        // Authenticate a user using Passport actingAs()
        $user = User::factory()->create();
        Passport::actingAs($user);

        // Set default headers for API requests
        $this->withHeaders([
            'Accept' => 'application/json',
            // Add more headers if needed
        ]);

//        $this->app['session']->put('process_store_id', '2bda0c37-4eac-44e5-a014-6c029d76dc62');
//        $this->app['request']->merge(['process_store_id' => '2bda0c37-4eac-44e5-a014-6c029d76dc62']);
    }


    /** @test */
    public function it_can_list_receiver():void
    {

        $domain = Domain::factory()->create();
        $sender = Sender::factory()->has(Receiver::factory())->create();

        $response = $this->getJson(route('receivers.index', $sender->id_sender));
        $responseData = $response->json();

        // dd($responseData['data'][0]['sender_id']);
        // Assert the response status
        $response->assertStatus(200); // Adjust based on your expected response status
        $this->assertEquals(  $sender->id_sender, $responseData['data'][0]['sender_id']);

    }


    /** @test */
  public function it_can_create_a_receiver():void
    {

        $domain = Domain::factory()->create();
        $newReceiver = Receiver::factory()->create();
        $newReceiver = $newReceiver->toArray();

      //  $receiverData = Receiver::factory()->create();
        $sender = Sender::factory()->create();
        // Make a POST request to create a task
      //dd("v1/sender/$sender->id_sender/");
        $response = $this->postJson(route('receivers.store', $sender->id_sender), $newReceiver);

       // dd($response);
        // Assert the response status
        $response->assertStatus(201); // Adjust based on your expected response status

       // $this->assertDatabaseHas('mm_receiver', ['receiver_title' => 'Mr']);


    }

    /** @test */
    public function it_can_show_a_receiver():void
    {
        // Create a test task
        $receiver = Receiver::factory()->create();
        $sender = Sender::factory()->create();

        // Make a GET request to fetch a specific task
        $response = $this->get("v1/sender/$sender->id_sender/receivers/" . $receiver->id_receiver);

        $response->assertStatus(200)
            ->assertJson([]);

    }
//
    /** @test */
    public function it_can_update_a_receiver():void
    {
        // Create a test task
        $receiver = Receiver::factory()->create();
        $sender = Sender::factory()->create();
        $receiver = $receiver->toArray();

        // Make a PUT request to update the task
        $response = $this->put(
            route('receivers.update', [ $sender->id_sender, $receiver['id_receiver'] ]),
            $receiver
        );


        $response->assertStatus(200);
    }
//
    /** @test */
    public function it_can_delete_a_receiver():void
    {
        // Create a test task
        $receiver = Receiver::factory()->create();
        $sender = Sender::factory()->create();


        // Make a DELETE request to delete the task
        $response = $this->delete("v1/sender/$sender->id_sender/receivers/" . $receiver->id_receiver);

        $response->assertStatus(204);
    }

    // Add more test methods for other functionalities of the TaskController as needed...
}
