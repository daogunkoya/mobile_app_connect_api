<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelPhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laravel_phones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('phoneable_id');
            $table->string('phoneable_type', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('mobile', 100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('phone', 100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->integer('is_valid')->nullable();
            $table->string('code', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laravel_phones');
    }
}
