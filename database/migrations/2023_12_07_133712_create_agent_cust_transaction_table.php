<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentCustTransactionTable extends Migration
{
    public function up()
    {
        Schema::create('agent_cust_transaction', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('receiptno', 60)->unique();
            $table->integer('cash');
            $table->string('sender_email', 60);
            $table->string('agent_email', 60);
            $table->string('sender_name', 60);
            $table->string('r_phone', 60);
            $table->string('r_name', 60);
            $table->double('total');
            $table->double('amt_send');
            $table->double('amt_local');
            $table->double('commission');
            $table->double('com_a');
            $table->double('com_d');
            $table->string('s_phone', 65);
            $table->string('r_bank', 65);
            $table->string('r_actno', 65);
            $table->string('r_idtype', 65);
            $table->string('status', 56);
            $table->float('exchange_rate');
            $table->string('r_transfer', 62);
            $table->string('agent_name', 60);
            $table->string('agent_ps', 60);
            $table->datetime('dtime');
            $table->string('address', 100);
            $table->string('postcode', 30);
            $table->string('town', 30);
            $table->string('county', 30);
            $table->string('country', 30);
            $table->string('clear', 20);
            $table->string('comp', 30);
            $table->string('del', 30);
            $table->string('sta', 30);
            $table->string('level', 40);
            $table->string('agid', 40);
            $table->string('agcid', 40);
            $table->text('note');
            $table->datetime('datepaid');
            $table->date('man_date');
            $table->integer('mc');
            $table->integer('agcrid');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('agent_cust_transaction');
    }
}

