<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Currency;

class CreateMmBankTable extends Migration
{
    public function up()
    {
        Schema::create('mm_bank', function (Blueprint $table) {
            $table->string('id', 38)->primary();
            $table->foreignIdFor(Currency::class, 'currency_id')->nullable(false)->nullable();
            $table->string('store_id', 200)->nullable();
            $table->string('name', 191);
            $table->string('bank_code', 191)->nullable();
            $table->string('bank_category', 191)->nullable();
            $table->integer('transfer_type')->default(1)->nullable();
            $table->integer('transfer_type_key')->default(0)->nullable();
            $table->integer('bank_proof_identity')->default(0)->nullable();  
            $table->integer('moderation_status')->default(1)->nullable();
            $table->integer('bank_status')->default(1)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mm_bank');
    }
}
