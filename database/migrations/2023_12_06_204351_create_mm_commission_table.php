<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMmCommissionTable extends Migration
{
    public function up()
    {
        Schema::create('mm_commission', function (Blueprint $table) {
            $table->string('id_commission', 36)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->primary();
            $table->string('store_id', 36)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('0');
            $table->string('user_id', 200)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('0');
            $table->string('currency_id', 36)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('1');
            $table->float('start_from')->default(0);
            $table->float('end_at')->default(0);
            $table->float('value');
            $table->string('agent_quota', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('50');
            $table->timestamps();
            $table->integer('commission_status')->default(1);
            $table->integer('moderation_status')->default(1);
        });
    }

    public function down()
    {
        Schema::dropIfExists('mm_commission');
    }
}
