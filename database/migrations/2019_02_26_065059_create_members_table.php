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
            $table->mediumInteger('parent_id')->unsigned()->nullable();
            $table->uuid('member_uuid')->index();
            $table->uuid('parent_member_uuid')->nullable();
            $table->string('full_name');
            $table->timestamps();
            $table->tinyInteger('level_id')->unsigned()->default(0);
            $table->tinyInteger('qualification_id')->unsigned()->default(0);
            $table->mediumInteger('child_counter')->unsigned()->default(0);
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
