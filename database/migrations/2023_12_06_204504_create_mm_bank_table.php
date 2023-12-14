<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMmBankTable extends Migration
{
    public function up()
    {
        Schema::create('mm_bank', function (Blueprint $table) {
            $table->string('id', 38)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->primary();
            $table->string('name', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('store_id', 200)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('bank_category', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('');
            $table->integer('transfer_type')->default(1);
            $table->integer('transfer_type_key')->default(0);
            $table->integer('bank_proof_identity')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mm_bank');
    }
}
