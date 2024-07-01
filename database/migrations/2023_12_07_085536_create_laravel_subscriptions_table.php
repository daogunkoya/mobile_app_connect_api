<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelSubscriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('laravel_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('start_at', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('ref', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->double('amount')->notNull();
            $table->double('others')->nullable();
            $table->string('payment_mode', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('cash');
            $table->integer('paid')->notNull();
            $table->string('months_duration', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->integer('notice')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('laravel_subscriptions');
    }
}

