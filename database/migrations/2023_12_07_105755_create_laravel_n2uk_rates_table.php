<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelN2ukRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laravel__n2uk_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->double('bou')->default(0);
            $table->double('sold')->default(0);
            $table->integer('user_id')->default(0);
            $table->timestamps();
            $table->index('user_id', 'laravel_n2uk_rates_user_id_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laravel__n2uk_rates');
    }
}
