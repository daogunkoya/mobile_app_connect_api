<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMmDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mm_domain', function (Blueprint $table) {
            $table->uuid('id_domain')->primary();
            $table->string('store_id',36)->nullable();
            $table->string('user_id',36)->nullable();
            $table->string('domain_name',200)->nullable();
            $table->string('domain_host',200)->nullable();
            $table->text('aws_resource_record',200)->nullable();
            $table->string('aws_ssl_arn',200)->nullable();
            $table->integer('domain_cname_verified');
            $table->integer('domain_ssl_verified');
            $table->string('domain_slug',200)->nullable();
            $table->integer('domain_local')->nullable();
            $table->integer('domain_default')->nullable();
            $table->integer('domain_verified')->nullable();
            $table->integer('domain_status')->length(1)->default('1');
            $table->integer('moderation_status')->length(1)->unsigned()->default('1');
            $table->integer('domain_count_view')->length(1)->unsigned()->default('1');
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
        Schema::dropIfExists('mm_domain');
    }
}
