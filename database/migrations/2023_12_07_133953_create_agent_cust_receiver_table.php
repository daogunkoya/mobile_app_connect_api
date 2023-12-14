<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentCustReceiverTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_cust_receiver', function (Blueprint $table) {
            $table->id();
            $table->string('agent_cust_email', 70)->nullable();
            $table->string('agent_email', 60)->nullable();
            $table->string('b_fname', 60);
            $table->string('b_lname', 60);
            $table->string('b_phone', 11)->nullable();
            $table->string('b_idtype', 60)->nullable();
            $table->string('b_transfer', 60);
            $table->string('b_pbank', 50)->nullable();
            $table->string('b_abank', 50)->nullable();
            $table->string('b_actno', 15);
            $table->string('date', 78);
            $table->string('c_name', 70)->nullable();
            $table->string('comp', 30)->nullable();
            $table->string('del', 90)->nullable();
            $table->string('sta', 30)->nullable();
            $table->string('level', 40)->nullable();
            $table->string('agid', 40);
            $table->string('agcid', 40);
            $table->string('bank', 100)->nullable();
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_cust_receiver');
    }
}

