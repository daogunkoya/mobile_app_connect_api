<?php

use App\Models\Currency;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMmUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mm_user', function (Blueprint $table) {
            $table->string('id_user')->primary();
            $table->string('title')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('user_name')->nullable();
            $table->string('dob')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->foreignIdFor(Currency::class, 'currency_id')->nullable();;
            $table->string('store_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('user_role_type')->default(1);
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
            $table->string('user_handle')->nullable();
            $table->char('active_currency_id', 40)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mm_user');
    }
}
