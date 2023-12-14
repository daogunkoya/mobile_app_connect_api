<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelSettingTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('laravel_setting_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('two_copies')->notNull()->default(0);
            $table->integer('allow_credit')->notNull()->default(0);
            $table->integer('send_email')->notNull()->default(0);
            $table->integer('agentname_onreceipt')->notNull()->default(0);
            $table->integer('cap_transaction')->notNull()->default(0);
            $table->double('max_transaction')->notNull()->default(1500);
            $table->integer('allow_sms')->notNull()->default(0);
            $table->string('sms_email', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('sms_hash', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->integer('allow_payment')->nullable();
            $table->double('max_payment')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('laravel_setting_transactions');
    }
}

