<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMmUserConfirmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mm_user_confirm', function (Blueprint $table) {
            $table->char('id_confirm', 36)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->primary();
            $table->string('store_id', 36)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('user_email', 36)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('confirm_type', 36)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('confirm_code', 200)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->text('confirm_token')->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->integer('confirm_status')->nullable();
            $table->integer('record_count_process')->nullable();
            $table->text('record_note')->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->unsignedInteger('record_count_update')->default(1);
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
        Schema::dropIfExists('mm_user_confirm');
    }
}

