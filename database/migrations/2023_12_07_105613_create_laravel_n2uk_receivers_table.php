<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelN2ukReceiversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laravel__n2uk_receivers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('sender_id')->unsigned();
            $table->string('fname');
            $table->string('lname');
            $table->string('phone');
            $table->string('transfer_type');
            $table->string('identity_type');
            $table->string('uk_bank');
            $table->string('uk_account_no');
            $table->string('uk_sort_code');
            $table->string('ng_account_name');
            $table->string('ng_bank');
            $table->string('ng_account_no');
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
        Schema::dropIfExists('laravel__n2uk_receivers');
    }
}
