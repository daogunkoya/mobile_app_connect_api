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
        Schema::create('status_change_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id'); // ID of the user who made the change
            $table->string('loggable_type'); // Type of the related model (User, Transaction, etc.)
            $table->uuid('loggable_id');   // ID of the related model
            $table->string('activity'); // New status value
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_change_logs');
    }
};
