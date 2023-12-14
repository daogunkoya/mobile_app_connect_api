<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelN2ukTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laravel__n2uk_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transfer_code');
            $table->string('user_id');
            $table->string('sender_id');
            $table->string('receiver_id');
            $table->double('amount_pounds');
            $table->double('amount_naira');
            $table->double('bou_rate');
            $table->double('sold_rate');
            $table->double('margin');
            $table->string('note')->default('unavailable');
            $table->string('status')->default('pending');
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
        Schema::dropIfExists('laravel__n2uk_transactions');
    }
}
