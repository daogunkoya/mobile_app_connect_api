<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMmTransactionCopyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mm_transaction_copy', function (Blueprint $table) {
            $table->increments('id_transaction');
            $table->string('receipt_number', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable(false);
            $table->string('type', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable(false);
            $table->string('user_id', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable(false);
            $table->string('sender_id', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable(false);
            $table->string('receiver_id', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable(false);
            $table->string('receiver_phone', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable()->default(null);
            $table->double('amount')->nullable(false)->default(0);
            $table->double('commission')->nullable(false);
            $table->double('agent_commission')->nullable(false);
            $table->double('exchange_rate')->nullable(false);
            $table->integer('currency_id')->nullable(false)->default(1);
            $table->string('currency_income', 100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable(false)->default('commission');
            $table->string('bou_rate', 100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable(false)->default('0');
            $table->string('sold_rate', 100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable(false)->default('0');
            $table->string('status', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable(false)->default('pending');
            $table->string('note', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable(false)->default('unavailable');
            $table->string('agent_payment_id', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable(false)->default('none');
            $table->timestamps();

            $table->index('receipt_number');
            $table->index('sender_id');
            $table->index('user_id');
            $table->index('receiver_phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mm_transaction_copy');
    }
}
