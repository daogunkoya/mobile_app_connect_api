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
        Schema::table('mm_store', function (Blueprint $table) {
            // Dropping columns
            $table->dropColumn([
                'store_business_type_id',
                'store_group_revenue_id',
                'store_group_industry_id',
                'store_business_crn',
                'store_user_first_name',
                'store_user_last_name',
                'store_user_phone',
                'store_user_address',
                'store_user_postcode',
                'store_user_city',
                'store_user_dob',
                'list_image',
                'store_admin_type',
                'store_user_email',
            ]);

            // Adding new columns
            $table->string('store_address')->nullable()->after('store_business_name');
            $table->string('store_slogan')->nullable()->after('store_name');
            $table->string('store_email')->nullable()->after('store_name');
            $table->string('store_postcode')->nullable()->after('store_address');
            $table->string('store_city')->nullable()->after('store_postcode');
            $table->string('store_country')->nullable()->after('store_city'); // Moved after store_city for consistency
            $table->string('store_phone')->nullable()->after('store_country');
            $table->string('store_mobile')->nullable()->after('store_phone');
            $table->string('store_url')->nullable()->after('store_mobile');
            $table->boolean('enable_sms')->default(false)->nullable()->after('store_mobile');
            $table->boolean('enable_multiple_receipt')->default(false)->nullable()->after('enable_sms');
            $table->boolean('enable_credit')->default(false)->nullable()->after('enable_multiple_receipt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mm_store', function (Blueprint $table) {
            // Adding the dropped columns back
            $table->integer('store_business_type_id')->nullable();
            $table->integer('store_group_revenue_id')->nullable();
            $table->integer('store_group_industry_id')->nullable();
            $table->string('store_business_crn')->nullable();
            $table->string('store_user_first_name')->nullable();
            $table->string('store_user_last_name')->nullable();
            $table->string('store_user_phone')->nullable();
            $table->string('store_user_address')->nullable();
            $table->string('store_user_postcode')->nullable();
            $table->string('store_user_city')->nullable();
            $table->date('store_user_dob')->nullable();
            $table->string('list_image')->nullable();
            $table->string('store_admin_type')->nullable();

            // Dropping the newly added columns
            $table->dropColumn([
                'store_address',
                'store_slogan',
                'store_postcode',
                'store_city',
                'store_country',
                'store_phone',
                'store_mobile',
                'store_url',
                'enable_sms',
                'enable_multiple_receipt',
                'enable_credit',
            ]);
        });
    }
};
