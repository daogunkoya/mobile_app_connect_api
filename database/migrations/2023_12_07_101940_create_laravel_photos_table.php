<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laravel_photos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('identity_proof', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable(false);
            $table->string('address_proof', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable(false);
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
        Schema::dropIfExists('laravel_photos');
    }
}
