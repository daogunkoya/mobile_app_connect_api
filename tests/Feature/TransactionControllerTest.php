<?php

namespace Tests\Feature;

use App\Models\AcceptableIdentity;
use App\Models\Bank;
use App\Models\Currency;
use App\Models\Transaction;
use JetBrains\PhpStorm\NoReturn;
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

class TransactionControllerTest extends TestCase
{
    protected Generator $faker;

    use RefreshDatabase;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        //$domainData = Store::Factory()->create();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();

        $this->artisan('db:seed', ['--class' => 'RateSeeder']);
        $this->artisan('db:seed', ['--class' => 'CommissionSeeder']);

        // Authenticate a user using Passport actingAs()
        $user = User::factory()->create(['user_role_type' => 3]);
        Passport::actingAs($user);

        // Set default headers for API requests
        $this->withHeaders([
            'Accept' => 'application/json',
            // Add more headers if needed
        ]);

    }


    /** @test */
    public function it_can_create_a_transaction():void
    {
//        exec('php artisan db:seed --class=RateSeeder');
//        exec('php artisan db:seed --class=CommissionSeeder');

        $domain = Domain::factory()->create();

        $newReceiver = Receiver::factory()->create();
        $amountSent = "600.00";
        $response = $this->postJson(route('transactions.store'),
            [
                "receiver_id" =>$newReceiver->id_receiver,
                "conversion_type" => 1,
                "amount_sent" =>  $amountSent
            ]);

            $responseData  = $response->json();


        // dd($response);
        // Assert the response status
        $response->assertStatus(200); // Adjust based on your expected response status

        $this->assertEquals( $responseData['data']['amount_sent'], $amountSent);

        $this->assertEquals( Transaction::latest()->value('amount_sent'), $amountSent);


    }

    /** @test */
    public function it_can_fetch_transaction_list():void
    {
        // Create a test task
        $transaction = Transaction::factory(5)->create();

        // Make a GET request to fetch a specific task
        $response = $this->get(route('transactions.index'));

        $response->assertStatus(200)
            ->assertJson([]);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'created_at',
                    'id_transaction',
                    'transaction_code',
                    'user_id',
                    'sender_id',
                    'sender_address',
                    // Add more keys if needed
                ]
            ]
        ]);

    }

    /** @test */
    public function it_can_show_a_transaction():void
    {
        // Create a test task
        $transaction = Transaction::factory()->create();

        // Make a GET request to fetch a specific task
        $response = $this->get(route('transactions.show', $transaction->id_transaction));

        $response->assertStatus(200)
            ->assertJson([]);
        $response->assertJsonStructure([
            'data' => [
                'created_at',
                'id_transaction',
                'transaction_code',
                'user_id',
                'sender_id',
                'sender_address',
                'receiver_address',
                'currency_id',
                'receiver_fname',
                'receiver_lname',
                'receiver_phone',
                // Add more keys if needed
            ]
        ]);

    }
//
    /** @test */
//    public function it_can_update_a_transaction():void
//    {
//
//    }
//
    /** @test */
//    public function it_can_delete_a_transaction():void
//    {
//
//    }

    // Add more test methods for other functionalities of the TaskController as needed...
}