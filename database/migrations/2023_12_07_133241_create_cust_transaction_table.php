<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cust_transaction', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('receiptno', 60)->unique();
            $table->integer('cash');
            $table->string('sender_email', 60);
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
            $table->dateTime('dtime');
            $table->string('comp', 30);
            $table->string('del', 30);
            $table->string('sta', 30);
            $table->integer('ecal');
            $table->integer('ecredit');
            $table->integer('level');
            $table->string('clear', 60);
            $table->string('cid', 40);
            $table->text('note');
            $table->integer('mc');
            $table->date('man_date');
            $table->integer('crid');
            $table->timestamps(); // Adds 'created_at' and 'updated_at' columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cust_transaction');
    }
}
