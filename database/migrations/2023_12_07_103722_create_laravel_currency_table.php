<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelCurrencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laravel_currency', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('origin');
            $table->string('origin_symbol');
            $table->string('destination');
            $table->string('destination_symbol');
            $table->integer('user_id');
            $table->string('income_category')->default('commission');
            $table->integer('status');
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
        Schema::dropIfExists('laravel_currency');
    }
}
