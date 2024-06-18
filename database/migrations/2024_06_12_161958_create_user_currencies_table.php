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
        Schema::create('mm_user_currencies', function (Blueprint $table) {
            $table->string('id')->primaty();
            $table->string('user_id');
            $table->string('currency_id');
            $table->timestamp('last_used_at')->useCurrent();

            $table->foreign('user_id')->references('id_user')->on('mm_user')->onDelete('cascade');
            $table->foreign('currency_id')->references('id_currency')->on('mm_currency')->onDelete('cascade');

            $table->unique(['user_id', 'currency_id']); // Ensures a user cannot have duplicate currency entries

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mm_user_currencies');
    }
};
