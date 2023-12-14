<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laravel_commissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('currency_id')->default(1);
            $table->double('start_from');
            $table->double('end_at');
            $table->string('value');
            $table->string('agent_quota')->default('50');
            $table->timestamps();
        });

        Schema::table('laravel_commissions', function (Blueprint $table) {
            $table->index('user_id', 'commissions_user_id_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laravel_commissions');
    }
}
