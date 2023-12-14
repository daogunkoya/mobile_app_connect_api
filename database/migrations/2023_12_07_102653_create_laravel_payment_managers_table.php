<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelPaymentManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laravel_payment_managers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('manager_id', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('payment_type', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('remittance');
            $table->string('manager_name', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->double('amount');
            $table->double('balance');
            $table->double('total');
            $table->integer('fully_paid')->default('0');
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
        Schema::dropIfExists('laravel_payment_managers');
    }
}
