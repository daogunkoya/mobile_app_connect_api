<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelRolesTable extends Migration
{
    public function up()
    {
        Schema::create('laravel_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('laravel_roles');
    }
}
