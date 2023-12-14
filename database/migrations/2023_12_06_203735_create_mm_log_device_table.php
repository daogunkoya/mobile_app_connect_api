<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMmLogDeviceTable extends Migration
{
    public function up()
    {
        Schema::create('mm_log_device', function (Blueprint $table) {
            $table->char('id_device', 36)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('');
            $table->string('store_id', 36)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('user_id', 36)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->integer('device_type')->nullable();
            $table->string('device_name', 199)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->integer('device_status')->default(0);
            $table->timestamps();

            $table->primary('id_device');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mm_log_device');
    }
}
