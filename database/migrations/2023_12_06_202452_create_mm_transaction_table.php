<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMmTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mm_transaction', function (Blueprint $table) {
            $table->uuid('id_transaction')->primary();
            $table->string('store_id', 36)->nullable();
            $table->string('user_id', 36)->nullable();
            $table->string('currency_id', 36)->nullable();
            $table->string('transaction_code', 199)->nullable();
            $table->string('sender_id', 36)->nullable();
            $table->string('receiver_id', 36)->nullable();
            $table->string('sender_fname', 200)->nullable();
            $table->string('sender_lname', 200)->nullable();
            $table->string('receiver_fname', 200)->nullable();
            $table->string('receiver_lname', 200)->nullable();
            $table->string('receiver_address', 200)->nullable();
            $table->string('receiver_bank_id', 36)->nullable();
            $table->string('receiver_account_no', 200)->nullable();
            $table->string('receiver_identity_id', 36)->nullable();
            $table->string('receiver_transfer_type', 200)->nullable();
            $table->string('sender_address', 200)->nullable();
            $table->string('agent_payment_id', 36)->nullable();
            $table->string('receiver_phone', 200)->nullable();
            $table->double('total_amount')->nullable();
            $table->double('amount_sent')->nullable();
            $table->double('local_amount')->nullable();
            $table->double('total_commission')->nullable();
            $table->double('agent_commission')->nullable();
            $table->double('exchange_rate');
            $table->double('bou_rate');
            $table->double('sold_rate');
            $table->double('currency_income');
            $table->text('note')->nullable();
            $table->integer('transaction_status')->nullable();
            $table->integer('transaction_type')->nullable();
            $table->unsignedInteger('moderation_status')->default(1);
            $table->unsignedInteger('record_count_update')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mm_transaction');
    }
}

