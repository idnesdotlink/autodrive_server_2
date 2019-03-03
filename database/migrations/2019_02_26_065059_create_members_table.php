<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->uuid('member_uuid')->index();
            $table->mediumInteger('parent_id')->unsigned()->nullable();
            $table->uuid('parent_member_uuid')->nullable();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken()->nullable();
            $table->timestamps();
            $table->string('phone_code')->unique()->nullable();
            $table->string('phone', 16)->unique()->nullable();
            $table->enum('gender', ['L', 'P'])->default('L');
            $table->char('village_id', 10)->nullable();
            $table->text('address')->nullable();
            $table->tinyInteger('level_id')->unsigned()->default(0);
            $table->tinyInteger('qualification_id')->unsigned()->default(0);
            $table->mediumInteger('children_counter')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
}
