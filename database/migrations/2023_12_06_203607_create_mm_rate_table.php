<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMmRateTable extends Migration
{
    public function up()
    {
        Schema::create('mm_rate', function (Blueprint $table) {
            $table->string('id_rate', 36)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('');
            $table->string('store_id', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('0');
            $table->string('user_id', 38)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('0');
            $table->string('main_rate', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('0');
            $table->string('bou_rate', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('0');
            $table->string('sold_rate', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('0');
            $table->string('currency_id', 36)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('');
            $table->integer('rate_status')->default(1);
            $table->integer('moderation_status')->default(1);
            $table->timestamps();

            $table->primary('id_rate');
            $table->index('user_id', 'rates_user_id_index');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mm_rate');
    }
}
