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
        Schema::create('mm_outstanding_payment', function (Blueprint $table) {
            // UUID as the primary key
            $table->uuid('id_outstanding')->primary();
            
            // Keeping related IDs as UUID (assuming UUID is used for other related entities)
            $table->uuid('store_id')->nullable();
            $table->uuid('user_id')->nullable();
            $table->uuid('transaction_id')->nullable();
            $table->uuid('currency_id')->nullable();
            $table->string('transaction_code')->nullable();
        
            
            // Correct precision for monetary values (up to 15 digits with 2 decimal places)
            $table->string('sender_name');
            $table->string('receiver_name');
            $table->decimal('total_amount', 15, 2);
            $table->decimal('amount_sent', 15, 2);
            $table->decimal('local_amount', 15, 2)->nullable();
            $table->decimal('total_commission', 15, 2)->nullable();
            $table->decimal('agent_commission', 15, 2)->nullable();
            $table->decimal('exchange_rate', 15, 8)->nullable(); // Exchange rates often require high precision
            $table->decimal('bou_rate', 15, 8)->nullable();
            $table->decimal('sold_rate', 15, 8)->nullable();
            
            // Boolean fields for statuses
            $table->boolean('transaction_paid_status')->default(false); 
            $table->boolean('commission_paid_status')->default(false); 
            $table->boolean('outstanding_status')->nullable();
            $table->boolean('moderation_status')->nullable();

            $table->timestamps();
            
            // Indexes for foreign keys
            $table->index('transaction_id');
            $table->index('currency_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mm_outstanding_payment');
    }
};
