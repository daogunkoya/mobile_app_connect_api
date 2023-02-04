<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMmTransactionsTable extends Migration
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
            $table->string('store_id',36)->nullable();
            $table->string('user_id',36)->nullable();
            $table->string('currency_id',36)->nullable();
            $table->string('sender_id',36)->nullable();
            $table->string('receiver_id',36)->nullable();
            $table->string('agent_payment_id',36)->nullable();
            $table->string('receiver_phone',200)->nullable();
            $table->double('total_amount')->nullable();
            $table->double('local_amount')->nullable();
            $table->double('total_commission',200)->nullable();
            $table->double('agent_commission',200)->nullable();
            $table->double('exchange_rate');
            $table->double('bou_rate');
            $table->double('sold_rate');
            $table->double('currency_income');
            $table->text('note')->nullable();
            $table->integer('transaction_status')->nullable();
            $table->integer('transaction_type')->nullable();
            $table->integer('moderation_status')->length(1)->unsigned()->default('1');
            $table->integer('record_count_update')->length(1)->unsigned()->default('1');
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
