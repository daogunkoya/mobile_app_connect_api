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
        Schema::table('mm_user', function (Blueprint $table) {
            $table->string('address')->nullable()->after('email');
            $table->string('postcode')->nullable()->after('email');
            $table->json('metadata')->nullable()->after('email');
            $table->string('photo_id')->nullable()->after('email');
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
