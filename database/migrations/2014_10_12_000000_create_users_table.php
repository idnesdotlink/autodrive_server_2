<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 6)->default('none');
            $table->id('uuid')->index()->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->string('phone_code', 6)->unique()->nullable();
            $table->string('phone', 16)->unique()->nullable();
            $table->char('gender', 1)->default('L');
            $table->char('village_id', 10)->nullable();
            $table->char('district_id', 7)->nullable();
            $table->char('regency_id', 4)->nullable();
            $table->char('province_id', 2)->nullable();
            $table->text('address')->nullable();
            $table->char('data_status', 1)->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
