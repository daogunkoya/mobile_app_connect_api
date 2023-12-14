<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMmStoreTable extends Migration
{
    public function up()
    {
        Schema::create('mm_store', function (Blueprint $table) {
            $table->char('id_store', 36)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('user_id', 36)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('store_name', 36)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('store_admin_type', 36)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('store_slug', 36)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->text('list_image')->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('store_user_email', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('store_business_name', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('store_business_vat', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('store_business_crn', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('store_business_type_id', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('store_group_revenue_id', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('store_group_industry_id', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->integer('version')->default(1);
            $table->string('store_user_first_name', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('store_user_last_name', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('store_user_phone', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('store_user_address', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('store_user_postcode', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('store_user_city', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('store_user_dob', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('social_facebook', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('social_twitter', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('social_linkedin', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('social_google', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('social_instagram', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('payment_status', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('payment_url', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->unsignedInteger('store_status')->default(1);
            $table->unsignedInteger('moderation_status')->default(1);
            $table->unsignedInteger('store_count_view')->default(1);
            $table->unsignedInteger('record_count_update')->default(1);
            $table->timestamps();

            $table->primary('id_store');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mm_store');
    }
}
