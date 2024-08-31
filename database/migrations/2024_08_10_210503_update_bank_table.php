<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Currency;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mm_bank', function (Blueprint $table) {
            $table->integer('moderation_status')->default(1)->after('transfer_type');
            $table->integer('bank_status')->default(1)->after('transfer_type');
            $table->foreignIdFor(Currency::class, 'currency_id')->nullable(false)->after('transfer_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
