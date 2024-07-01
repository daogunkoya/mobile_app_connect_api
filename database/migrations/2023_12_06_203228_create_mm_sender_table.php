<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMmSenderTable extends Migration
{
    public function up()
    {
        Schema::create('mm_sender', function (Blueprint $table) {
            $table->string('id_sender', 191)->default('');
            $table->string('user_id', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('store_id', 191)->default('2bda0c37-4eac-44e5-a014-6c029d76dc62');
            $table->string('sender_title', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('');
            $table->string('sender_slug', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('');
            $table->string('sender_fname', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('');
            $table->string('sender_mname', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('sender_lname', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('');
            $table->string('sender_dob', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('');
            $table->integer('currency_id')->default(1);
            $table->string('sender_email', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('sender_phone', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('sender_mobile', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('');
            $table->string('sender_address', 200)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('sender_postcode', 200)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('photo_id', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();;
            $table->integer('sender_status')->default(1);
            $table->integer('moderation_status')->default(1);
            $table->timestamps();

            $table->primary('id_sender');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mm_sender');
    }
}
