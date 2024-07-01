<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMmLogConnectTable extends Migration
{
    public function up()
    {
        Schema::create('mm_log_connect', function (Blueprint $table) {
            $table->char('id_log', 36)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('');
            $table->longText('request_method')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('response_code', 199)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->longText('content')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->longText('request_destination')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->longText('request_origin')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->longText('request_message')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->longText('response_message')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->tinyInteger('request_type')->nullable();
            $table->longText('user_id')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('store_id', 36)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('store_name', 199)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->integer('count_last_request')->nullable();
            $table->timestamps();

            $table->primary('id_log');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mm_log_connect');
    }
}
