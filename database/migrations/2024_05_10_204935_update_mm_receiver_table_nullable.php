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
        Schema::table('mm_receiver', function (Blueprint $table) {
            $table->string('transfer_type')->nullable()->change();
            // $table->string('transfer_type_id')->nullable()->change();
            $table->string('currency_id')->nullable()->change();
            $table->string('identity_type_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mm_receiver', function (Blueprint $table) {
            $table->string('transfer_type')->nullable()->change();
            // $table->string('transfer_type_id')->nullable()->change();
            $table->string('currency_id')->nullable()->change();
            $table->string('identity_type_id')->nullable()->change();
        });
    }
};
