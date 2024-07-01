<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelAgentCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laravel_agent_customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id');
            $table->string('title');
            $table->string('name');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('lname');
            $table->string('dob');
            $table->integer('currency_id')->default(1);
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile');
            $table->integer('address_id');
            $table->string('address')->nullable();
            $table->string('postcode')->nullable();
            $table->string('photo_id');
            $table->timestamps();
        });

        // Add index
        Schema::table('laravel_agent_customers', function (Blueprint $table) {
            $table->index('phone');
            $table->index('mobile');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laravel_agent_customers');
    }
}
