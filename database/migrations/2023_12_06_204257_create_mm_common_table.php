<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMmCommonTable extends Migration
{
    public function up()
    {
        Schema::create('mm_common_table', function (Blueprint $table) {
            $table->char('id_log', 36)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->primary();
            $table->string('store_id', 36)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('user_id', 36)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mm_common_table');
    }
}
