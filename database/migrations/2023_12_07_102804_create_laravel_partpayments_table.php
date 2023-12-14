<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelPartpaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laravel_partpayments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('manager_id', 190)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->integer('admin_payment_id')->default('0');
            $table->string('payment_type', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('transaction');
            $table->double('amount');
            $table->integer('fully_paid')->default('0');
            $table->timestamps();
        });

        // Add an index on fully_paid column
        Schema::table('laravel_partpayments', function (Blueprint $table) {
            $table->index('fully_paid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laravel_partpayments');
    }
}
