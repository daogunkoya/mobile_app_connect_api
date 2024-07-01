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
        Schema::create('logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('channel');
            $table->string('level');
            $table->text('message');
            $table->text('context')->nullable();
            $table->string('request_method')->nullable();
            $table->string('request_url')->nullable();
            $table->string('request_ip')->nullable();
            $table->text('request_body')->nullable();
            $table->text('response_data')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
