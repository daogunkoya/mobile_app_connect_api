<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyBouRateAndSoldRateNullableInMmRateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mm_rate', function (Blueprint $table) {
            $table->decimal('bou_rate', 8, 2)->nullable()->change();
            $table->decimal('sold_rate', 8, 2)->nullable()->change();
            $table->string('member_user_id')->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mm_rate', function (Blueprint $table) {
            $table->decimal('bou_rate', 8, 2)->nullable(false)->change();
            $table->decimal('sold_rate', 8, 2)->nullable(false)->change();
            $table->string('admin_user_id');
        });
    }
}
