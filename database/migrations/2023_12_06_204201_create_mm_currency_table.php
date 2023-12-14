<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMmCurrencyTable extends Migration
{
    public function up()
    {
        Schema::create('mm_currency', function (Blueprint $table) {
            $table->string('id_currency', 200)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->primary();
            $table->string('store_id', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('user_id', 200)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('');
            $table->string('currency_code', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('currency_origin', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('currency_origin_symbol', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('currency_destination', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('currency_destination_symbol', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('income_category', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('commission');
            $table->integer('currency_status')->default(1);
            $table->integer('default_currency')->default(0);
            $table->integer('moderation_status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mm_currency');
    }
}
