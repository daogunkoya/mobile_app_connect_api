<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laravel_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('user_type', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->integer('paymentable_id');
            $table->string('paymentable_type', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('ref', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->double('amount');
            $table->string('for', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('mode', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('cash');
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
        Schema::dropIfExists('laravel_payments');
    }
}
