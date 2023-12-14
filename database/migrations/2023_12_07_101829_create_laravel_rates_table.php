<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laravel_rates', function (Blueprint $table) {
            $table->string('id', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->primary();
            $table->string('rate', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('0');
            $table->string('bou_rate', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('0');
            $table->string('sold_rate', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('0');
            $table->integer('user_id')->default(0);
            $table->integer('currency_id');
            $table->timestamps();

            $table->index('user_id', 'rates_user_id_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laravel_rates');
    }
}
