<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMmStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

          
        Schema::create('mm_store', function (Blueprint $table) {
            $table->uuid('id_store')->primary();
            $table->string('user_id',36)->nullable();
            $table->string('store_name',36)->nullable();
            $table->string('store_admin_type',36)->nullable();
            $table->string('store_slug',36)->nullable();
            $table->text('list_image')->nullable();
            $table->string('store_user_email')->nullable();
            $table->string('store_business_name')->nullable();
            $table->string('store_business_vat')->nullable();
            $table->string('store_business_crn')->nullable();
            $table->string('store_business_type_id')->nullable();
            $table->string('store_group_revenue_id')->nullable();
            $table->string('store_group_industry_id')->nullable();
            $table->string('store_user_first_name')->nullable();
            $table->string('store_user_last_name')->nullable();
            $table->string('store_user_phone')->nullable();
            $table->string('store_user_address')->nullable();
            $table->string('store_user_postcode')->nullable();
            $table->string('store_user_city')->nullable();
            $table->string('store_user_dob')->nullable();
            $table->string('social_facebook')->nullable();
            $table->string('social_twitter')->nullable();
            $table->string('social_linkedin')->nullable();
            $table->string('social_google')->nullable();
            $table->string('social_instagram')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('payment_url')->nullable();
            $table->integer('store_status')->length(1)->unsigned()->default('1');
            $table->integer('moderation_status')->length(1)->unsigned()->default('1');
            $table->integer('store_count_view')->length(1)->unsigned()->default('1');
            $table->integer('record_count_update')->length(1)->unsigned()->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mm_store');
    }
}
