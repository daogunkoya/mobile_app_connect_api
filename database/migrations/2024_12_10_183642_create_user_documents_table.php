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
        Schema::create('mm_user_documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            //$table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('user_id');
            $table->string('document_type'); // e.g., ID, Passport, Driver License
            $table->string('file_path'); // Path to the file
            $table->string('original_name'); // Original file name
            $table->string('mime_type'); // File MIME type
            $table->integer('file_size'); // File size in KB
            $table->string('status')->default('pending'); // pending, processed, failed
            $table->text('verification_result')->nullable(); // Third-party result
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mm_user_documents');
    }
};
