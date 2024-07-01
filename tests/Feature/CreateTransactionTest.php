<?php

namespace Tests\Feature;

use App\Actions\CreateTransaction;
use App\Actions\TransactionFulfilled;
use App\Collections\TransactionCollection;
use App\DTO\CommissionDto;
use App\DTO\RateDto;
use App\DTO\ReceiverDto;
use App\DTO\UserDto;
use App\Models\Domain;
use App\Models\Receiver;
use App\Models\Sender;
use App\Models\Transaction;
use App\Models\User;
use App\Payment\Contracts\PendingPayment;
use App\Payment\CreatePaymentForTransactionInMemory;
use App\Payment\CreatePaymentForTransactionInterface;
use App\Payment\InMemoryGateway;
use App\Repositories\CommissionRepository;
use App\Repositories\RateRepository;
use Database\Factories\ReceiverFactory;
use Faker\Factory as Faker;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CreateTransactionTest extends TestCase
{
    protected Generator $faker;
    use RefreshDatabase;
  //  use DatabaseMigrations;

    protected function setUp(): void
    {


        parent::setUp();
        $this->faker = Faker::create();

        $this->artisan('db:seed', ['--class' => 'RateSeeder']);
        $this->artisan('db:seed', ['--class' => 'CommissionSeeder']);
       // $this->artisan('db:seed');

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
    public function it_creates_transaction(): void
    {
        Mail::fake();



        $domain = Domain::factory()->create(['id_domain' => Str::uuid()]);


        $amountSent = 90;
//        $newReceiverFactory = ReceiverFactory::new()->create(['id_receiver' => Str::uuid(),
//            'sender_id' => Str::uuid(),
//            'receiver_slug'=>Str::slug('test')]);
//        $newReceiver  = $newReceiverFactory->load('sender');

        $sender = Sender::factory()->has(Receiver::factory())->create(['id_sender' => Str::uuid()]);
        $sender->load('receiver.sender');
        $newReceiver = $sender->receiver->first();
        $newReceiver->load('sender');
        $newReceiver->sender = $sender;

        $paymentToken = Str::uuid();

        $userRate = RateRepository::fetchTodaysRate(auth()->id());
        $userCommission = CommissionRepository::getCommissionValue($amountSent, auth()->id());

        $userDto = UserDto::fromEloquentModel(auth()->user());
        //dd($newReceiver->id_receiver);
        $receiverDto = ReceiverDto::fromEloquentModel($newReceiver);

        $transactionCollection = TransactionCollection::processTransactionData(
            RateDto::fromEloquentModel(($userRate)),
            CommissionDto::fromEloquentModel($userCommission),
            $amountSent,
            1);

        $createPayment = new CreatePaymentForTransactionInMemory();
        $this->app->instance(CreatePaymentForTransactionInterface::class,$createPayment);
        $pendingPayment = new PendingPayment(new InMemoryGateway(), $paymentToken);
        $createTransaction = app(CreateTransaction::class);

        $transaction = $createTransaction->handle(
            $transactionCollection,
            $pendingPayment,
            $receiverDto,
            $userDto);

//        Event::fake();
//        Event::assertDispatched(TransactionFulfilled::class, function(TransactionFulfilled $event)use($userDto, $transaction){
//            return $event->transaction === $transaction && $event->user === $userDto;
//        });

        $databaseTransaction = Transaction::latest()->first();

        $this->assertEquals($databaseTransaction->amount_sent,$amountSent);
        $this->assertEquals($sender->id_sender,$databaseTransaction->sender_id);

    }


}
