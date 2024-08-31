<?php

use App\Models\Transaction;
use App\Models\User;
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
        Schema::create('mm_payment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('total_in_pence');
            
            // Changed to string for flexibility in future additions
            $table->string('status');  
            $table->string('payment_gateway');
            $table->morphs('paymentable'); // Creates paymentable_id and paymentable_type
            $table->integer('payment_for')->default(1);  // Assuming this remains categorical but flexible
            $table->boolean('fully_paid_status')->default(false); 

            $table->foreignIdFor(Transaction::class, 'transaction_id');
            $table->foreignIdFor(User::class, 'user_id');
            $table->foreignIdFor(User::class, 'admin_id')->nullable();
            $table->foreignIdFor(User::class, 'manager_id')->nullable();

            $table->boolean('moderation_status')->default(false)->nullable();
            
            $table->timestamps();
            
            // Indexes for foreign keys
            $table->index('transaction_id');
            $table->index('user_id');
            $table->index('admin_id');
            $table->index('manager_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mm_payment');
    }
};
