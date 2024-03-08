<?php

// database/factories/SenderFactory.php

namespace Database\Factories;

use App\Models\Bank;
use App\Models\Currency;
use App\Models\Receiver;
use App\Models\Sender;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::inRandomOrder()->first();

        // Find or create a sender for the user
        $sender = Sender::factory()->create(['user_id' => $user->id_user]);

        // Find or create a receiver for the sender
        $receiver = Receiver::factory()->create(['sender_id' => $sender->id_sender]);

        $currency = Currency::factory()->create();

        return [
            'user_id' => $user->id_user,
            'currency_id'=> $currency->id_currency,
            'transaction_code' => $this->faker->unique()->word(),
            'sender_id' => $sender->id_sender,
            'receiver_id' => $receiver->id_receiver,
            'sender_fname' => $sender->sender_fname,
            'sender_lname' => $sender->sender_lname,
            'receiver_fname' =>$receiver->receiver_fname,
            'receiver_lname' => $receiver->receiver_lname,
            'receiver_address' =>$receiver->receiver_address,
            'receiver_bank_id' => Bank::factory()->create()->id,
            'receiver_account_no' => $receiver->account_number,
            'receiver_identity_id' => $receiver->identity_type_id,
            'receiver_transfer_type' => $receiver->transfer_type,
            'sender_address' => $sender->sender_address,
            'agent_payment_id' => '10000',
            'receiver_phone' => $receiver->receiver_phone,
            'total_amount' => $this->faker->randomNumber(3),
            'amount_sent' =>$this->faker->randomNumber(3),
            'local_amount' =>$this->faker->randomNumber(3),
            'total_commission' =>$this->faker->randomNumber(2),
            'agent_commission' => $this->faker->numberBetween(0, 100),
            'exchange_rate' => $this->faker->randomNumber(2),
            'bou_rate' => $this->faker->randomNumber(2),
            'sold_rate' => $this->faker->randomNumber(2),
            'currency_income' => 1,
            'note' => "None available",
            'transaction_status' => 1,
            'transaction_type' => 1 ,
            'moderation_status' => 1 ,
            'record_count_update' => 0,
            // Add other required fields and their fake data
        ];
    }
}
