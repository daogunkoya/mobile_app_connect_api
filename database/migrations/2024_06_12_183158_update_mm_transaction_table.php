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
        Schema::table('mm_transaction', function (Blueprint $table) {
            // Rename the existing currency_id column to origin_currency_id
            $table->renameColumn('currency_id', 'origin_currency_id');

            // Add the new destination_currency_id column
            $table->string('destination_currency_id')->after('transaction_code');

            // Assuming you have a currencies table and you want to set up foreign keys
            // $table->foreign('origin_currency_id');
            // $table->foreign('destination_currency_id');
            // $table->foreign('origin_currency_id')->references('id_currency')->on('mm_currency')->onDelete('cascade');
            // $table->foreign('destination_currency_id')->references('id_currency')->on('mm_currency')->onDelete('cascade');
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
