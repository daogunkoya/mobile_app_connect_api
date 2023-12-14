<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMmAddressTable extends Migration
{
    public function up()
    {
        Schema::create('mm_address', function (Blueprint $table) {
            $table->char('id', 36)->collation('utf8mb4_unicode_ci')->primary();
            $table->string('store_id', 255)->collation('utf8mb4_unicode_ci');
            $table->string('user_id', 255)->collation('utf8mb4_unicode_ci');
            $table->string('address1', 255)->collation('utf8mb4_unicode_ci');
            $table->string('address2', 255)->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('address3', 255)->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('city', 255)->collation('utf8mb4_unicode_ci');
            $table->string('state', 255)->collation('utf8mb4_unicode_ci');
            $table->string('post_code', 255)->collation('utf8mb4_unicode_ci');
            $table->string('country', 255)->collation('utf8mb4_unicode_ci');
            $table->char('sender_id', 36)->collation('utf8mb4_unicode_ci');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mm_address');
    }
}
