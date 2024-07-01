<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelUsersTable extends Migration
{
    public function up()
    {
        Schema::create('laravel_users', function (Blueprint $table) {
            $table->id();
            $table->string('title', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('name', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('fname', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('lname', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('mname', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('dob', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->integer('currency_id')->unsigned();
            $table->string('phone', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('mobile', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('email', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->unique();
            $table->string('type', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('customer');
            $table->double('credit')->default(0);
            $table->string('password', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('photo_id', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('0');
            $table->integer('address_id')->default(0);
            $table->integer('is_active')->default(0);
            $table->string('remember_token', 100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('laravel_users');
    }
}
