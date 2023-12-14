<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelRenewalsTable extends Migration
{
    public function up()
    {
        Schema::create('laravel_renewals', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 191)->default('null');
            $table->string('amount', 191)->default('null');
            $table->dateTime('next_payment');
            $table->string('payment_mode', 191)->default('null');
            $table->integer('completed')->default(0);
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('laravel_renewals');
    }
}
