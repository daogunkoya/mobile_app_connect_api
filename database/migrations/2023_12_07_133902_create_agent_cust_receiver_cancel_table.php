<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentCustReceiverCancelTable extends Migration
{
    public function up()
    {
        Schema::create('agent_cust_receiver_cancel', function (Blueprint $table) {
            $table->id();
            $table->string('agent_cust_email', 70);
            $table->string('agent_email', 60);
            $table->string('b_fname', 60);
            $table->string('b_lname', 60);
            $table->string('b_phone', 11);
            $table->string('b_idtype', 60);
            $table->string('b_transfer', 60);
            $table->string('b_pbank', 50);
            $table->string('b_abank', 50);
            $table->string('b_actno', 15);
            $table->string('date', 78);
            $table->string('c_name', 70);
            $table->string('comp', 30);
            $table->string('del', 30);
            $table->string('sta', 30);
            $table->string('level', 40);
            $table->string('agid', 40);
            $table->string('agcid', 40);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('agent_cust_receiver_cancel');
    }
}
