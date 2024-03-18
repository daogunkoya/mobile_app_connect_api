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
            $table->string('id_user')->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->primary();
            $table->string('first_name')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('last_name')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('user_name')->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('email')->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->unique();
            $table->foreignIdFor(Currency::class, 'currency_id');
            $table->string('store_id')->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->integer('user_role_type')->default(1);
            $table->string('remember_token', 100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->timestamps();
            $table->string('user_handle')->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->char('active_currency_id', 40)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
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
