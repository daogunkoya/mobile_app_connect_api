<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mm_currency', function (Blueprint $table) {
            // Drop the old columns
            $table->dropColumn('currency_origin');
            $table->dropColumn('currency_origin_symbol');
            $table->dropColumn('currency_destination_symbol');
            $table->dropColumn('currency_code');
            
            $table->renameColumn('currency_destination', 'currency_country');
            // Add the new columns
            $table->string('currency_type')->after('currency_code');
            $table->string('currency_symbol')->after('currency_code');
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
