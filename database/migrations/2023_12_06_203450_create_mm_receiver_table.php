<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMmReceiverTable extends Migration
{
    public function up()
    {
        Schema::create('mm_receiver', function (Blueprint $table) {
            $table->string('id_receiver', 191)->default('');
            $table->string('store_id', 191)->default('2bda0c37-4eac-44e5-a014-6c029d76dc62');
            $table->string('sender_id', 191)->default('');
            $table->string('receiver_title', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('');
            $table->string('receiver_slug', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('receiver_fname', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('');
            $table->string('receiver_mname', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('receiver_lname', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('');
            $table->string('currency_id', 191)->default('1');
            $table->string('receiver_email', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('receiver_phone', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('receiver_address', 200)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('transfer_type', 30)->charset('utf8mb3')->collation('utf8mb3_general_ci')->default('bank');
            $table->string('account_number', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('identity_type_id', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('bank_id', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->integer('receiver_status')->default(1);
            $table->integer('moderation_status')->default(1);
            $table->timestamps();

            $table->primary('id_receiver');
            $table->index('bank_id', 'idx_bank_id');
            $table->index('identity_type_id', 'idx_identity_type_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mm_receiver');
    }
}
