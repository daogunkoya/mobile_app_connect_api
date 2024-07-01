<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMmDomainTable extends Migration
{
    public function up()
    {
        Schema::create('mm_domain', function (Blueprint $table) {
            $table->char('id_domain', 36)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->primary();
            $table->string('store_id', 36)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('user_id', 36)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('domain_name', 200)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('domain_host', 200)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->text('aws_resource_record')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('aws_ssl_arn', 200)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->integer('domain_cname_verified')->default(0);
            $table->integer('domain_ssl_verified')->default(0);
            $table->string('domain_slug', 200)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->integer('domain_local')->nullable();
            $table->integer('domain_default')->nullable();
            $table->integer('domain_verified')->nullable();
            $table->integer('domain_status')->default(1);
            $table->integer('moderation_status')->unsigned()->default(1);
            $table->unsignedInteger('domain_count_view')->default(1);
            $table->unsignedInteger('record_count_update')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mm_domain');
    }
}
