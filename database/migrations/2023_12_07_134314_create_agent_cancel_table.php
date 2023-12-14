<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentCancelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_cancel', function (Blueprint $table) {
            $table->id();
            $table->double('customer_id');
            $table->string('fname', 60);
            $table->string('mname', 60);
            $table->string('lname', 60);
            $table->string('dob', 20);
            $table->string('email', 30);
            $table->string('pnumber', 15);
            $table->string('mnumber', 15);
            $table->string('address', 300);
            $table->string('postcode', 32);
            $table->string('hash', 80);
            $table->integer('active');
            $table->string('receiver1', 65);
            $table->string('receiver2', 65);
            $table->string('receiver3', 65);
            $table->string('password', 70);
            $table->string('proofid_name', 30);
            $table->integer('proofid_size');
            $table->string('proofid_type', 30);
            $table->mediumText('proofid_content');
            $table->string('proofad_name', 30);
            $table->integer('proofad_size');
            $table->string('proofad_type', 30);
            $table->mediumText('proofad_content');
            $table->string('type', 80);
            $table->date('date_reg');
            $table->string('title', 20);
            $table->string('company', 30);
            $table->string('line1', 30);
            $table->string('line2', 30);
            $table->string('line3', 30);
            $table->string('town', 30);
            $table->string('county', 30);
            $table->string('country', 30);
            $table->string('youknow', 40);
            $table->string('comp', 30);
            $table->string('del', 30);
            $table->string('sta', 30);
            $table->string('agrs', 20);
            $table->integer('level');
            $table->string('credit', 40);
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
        Schema::dropIfExists('agent_cancel');
    }
}
