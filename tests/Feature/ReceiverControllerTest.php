<?php

namespace Tests\Feature;

use App\Models\AcceptableIdentity;
use App\Models\Bank;
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
use Database\Seeders\StoreSeeder;

class ReceiverControllerTest extends TestCase
{
    protected $faker;

   // use RefreshDatabase;

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
    public function it_can_create_a_receiver()
    {
// Create task data
        $receiverData = [
            "process_store_id"=>"2bda0c37-4eac-44e5-a014-6c029d76dc62",
            'store_id'=>'2bda0c37-4eac-44e5-a014-6c029d76dc62',
            "receiver_address" => "12 ril street",
            "receiver_dob" => "2023-11-18",
            "receiver_email"=> "newma@jio.com",
            "receiver_fname"=> "lop",
            "receiver_lname"=> "truck",
            "receiver_mname"=> "toke",
            "receiver_mobile"=> "08767890",
            "receiver_phone"=> "098767890",
            "receiver_postcode"=> "tyy67",
            "receiver_title"=> "Mr",
             'transfer_type' =>"bank",
            'account_number' => $this->faker->randomNumber(),
            'currency_id' =>$this->faker->randomNumber(),
            'bank_id' => Bank::factory()->create()->id,
            'identity_type_id' => AcceptableIdentity::factory()->create()->id,
        ];

      //  $receiverData = Receiver::factory()->create();

        // Make a POST request to create a task
        $response = $this->postJson('v1/sender/1d58e4d4-175c-4f7d-8e57-aa2695127f57/receivers/', $receiverData);

        // Assert the response status
        $response->assertStatus(201); // Adjust based on your expected response status

       // $this->assertDatabaseHas('mm_receiver', ['receiver_title' => 'Mr']);


    }

    /** @test */
    public function it_can_show_a_receiver()
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
    public function it_can_update_a_receiver()
    {
        // Create a test task
        $receiver = Receiver::factory()->create();
        $sender = Sender::factory()->create();

        // Make a PUT request to update the task
        $response = $this->put("v1/sender/$sender->id_sender/receivers/" . $receiver->id_receiver, $receiver->toArray());

        $response->assertStatus(200);
    }
//
    /** @test */
    public function it_can_delete_a_receiver()
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
