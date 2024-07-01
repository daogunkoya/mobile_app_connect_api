<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use App\Models\User;
use App\Models\Sender;

class SenderControllerTest extends TestCase
{

    // use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

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
    public function it_can_create_a_sender()
    {

// Create task data
        $senderData = [
            "process_store_id"=>"2bda0c37-4eac-44e5-a014-6c029d76dc62",
            'store_id'=>'2bda0c37-4eac-44e5-a014-6c029d76dc62',
            "sender_address" => "12 ril street",
            "sender_dob" => "2023-11-18",
            "sender_email"=> "newma@jio.com",
            "sender_fname"=> "lop",
            "sender_lname"=> "truck",
            "sender_mname"=> "toke",
            "sender_mobile"=> "08767890",
            "sender_phone"=> "098767890",
            "sender_postcode"=> "tyy67",
            "sender_title"=> "Mr",
            "photo_id" => "test"
        ];
        $senderData = Sender::factory()->create(["photo_id" => "test"]);
        // Make a POST request to create a task
        $response = $this->postJson('v1/senders', $senderData->toArray());

        // Assert the response status
        $response->assertStatus(201); // Adjust based on your expected response status

      //  $this->assertDatabaseHas('mm_sender', ['sender_title' => 'Mr']);


    }

    /** @test */
    public function it_can_show_a_sender()
    {
        // Create a test task
        $sender = Sender::factory()->create();

        // Make a GET request to fetch a specific task
        $response = $this->get('v1/senders/' . $sender->id_sender);

        $response->assertStatus(200)
            ->assertJson([]);

    }
//
    /** @test */
    public function it_can_update_a_sender()
    {
        // Create a test task
        $sender = Sender::factory()->create([
            "photo_id" => "test"]);


        // Make a PUT request to update the task
        $response = $this->put('v1/senders/' . $sender->id_sender, $sender->toArray());

        $response->assertStatus(200);
    }
//
    /** @test */
    public function it_can_delete_a_sender()
    {
        // Create a test task
        $sender = Sender::factory()->create();


        // Make a DELETE request to delete the task
        $response = $this->delete('v1/senders/' . $sender->id_sender);

        $response->assertStatus(204);
    }

    // Add more test methods for other functionalities of the TaskController as needed...
}
