<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelN2ukCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laravel__n2uk_customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id');
            $table->string('title');
            $table->string('name')->nullable();
            $table->string('fname');
            $table->string('mname');
            $table->string('lname');
            $table->string('dob');
            $table->string('email');
            $table->string('phone');
            $table->string('mobile');
            $table->string('postcode');
            $table->string('address');
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
        Schema::dropIfExists('laravel__n2uk_customers');
    }
}
