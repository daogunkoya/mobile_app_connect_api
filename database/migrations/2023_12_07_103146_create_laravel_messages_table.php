<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laravel_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('messageable_id');
            $table->string('messageable_type');
            $table->string('phone')->default('-');
            $table->string('email')->default('-');
            $table->string('subject')->default('-')->nullable();
            $table->string('body', 800)->default('');
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
        Schema::dropIfExists('laravel_messages');
    }
}
