<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelReceiversTable extends Migration
{
    public function up()
    {
        Schema::create('laravel_receivers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('sender_id', 191)->collation('utf8mb4_unicode_ci');
            $table->string('fname', 191)->collation('utf8mb4_unicode_ci');
            $table->string('lname', 191)->collation('utf8mb4_unicode_ci');
            $table->string('phone', 191)->collation('utf8mb4_unicode_ci');
            $table->string('transfer_type', 191)->nullable()->collation('utf8mb4_unicode_ci');
            $table->string('identity_type', 191)->collation('utf8mb4_unicode_ci');
            $table->string('bank', 191)->collation('utf8mb4_unicode_ci');
            $table->string('account_number', 191)->collation('utf8mb4_unicode_ci');
            $table->timestamps();

            $table->index('user_id');
            $table->index('phone');
            $table->index('fname');
            $table->index('lname');
        });
    }

    public function down()
    {
        Schema::dropIfExists('laravel_receivers');
    }
}
