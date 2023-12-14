<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('laravel_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('type', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('user_id', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('sender_id', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('receiver_id', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('receiver_phone', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->double('amount')->default(0);
            $table->double('commission');
            $table->double('agent_commission');
            $table->double('exchange_rate');
            $table->integer('currency_id')->unsigned()->default(1);
            $table->string('currency_income', 100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('commission');
            $table->string('bou_rate', 100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('0');
            $table->string('sold_rate', 100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('0');
            $table->string('status', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('pending');
            $table->string('note', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('unavailable');
            $table->string('agent_payment_id', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('none');
            $table->timestamps();

            $table->index('receipt_number');
            $table->index('sender_id');
            $table->index('user_id');
            $table->index('receiver_phone');
        });
    }

    public function down()
    {
        Schema::dropIfExists('laravel_transactions');
    }
}

