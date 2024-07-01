<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMmAcceptableIdentityTable extends Migration
{
    public function up()
    {
        Schema::create('mm_acceptable_identity', function (Blueprint $table) {
            $table->string('id', 38)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->primary();
            $table->string('name', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('store_id', 200)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mm_acceptable_identity');
    }
}
