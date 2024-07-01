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
            $table->string('status');
            $table->string('payment_gateway');
            $table->string('payment_id');
            $table->foreignIdFor(Transaction::class, 'transaction_id');
            $table->foreignIdFor(User::class, 'user_id');
            $table->timestamps();
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
