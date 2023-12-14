<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMmUserDeviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mm_user_device', function (Blueprint $table) {
            $table->char('id_device', 36)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->primary();
            $table->string('store_id', 36)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('user_id', 36)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->integer('device_push_type')->nullable();
            $table->integer('device_type')->nullable();
            $table->string('device_code', 199)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('device_name', 199)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('device_location', 199)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('device_ip', 199)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->longText('device_access_token')->nullable();
            $table->string('device_push_token', 199)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('device_last_active', 199)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->integer('device_status')->nullable();
            $table->integer('record_count_update')->nullable();
            $table->integer('record_count_process')->nullable();
            $table->longText('record_note')->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('user_access_id', 199)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
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
        Schema::dropIfExists('mm_user_device');
    }
}

