<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelOutstandingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laravel_outstandings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('transaction_id');
            $table->integer('user_id');
            $table->double('amount');
            $table->double('agent_commission');
            $table->integer('manager_id');
            $table->integer('admin_id');
            $table->integer('transaction_paid');
            $table->integer('commission_paid');
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
        Schema::dropIfExists('laravel_outstandings');
    }
}
