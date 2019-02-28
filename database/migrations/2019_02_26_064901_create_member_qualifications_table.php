<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberQualificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_qualifications', function (Blueprint $table) {
            $table->mediumInteger('member_id')->unsigned();
            $table->string('name', 128);
            $table->mediumInteger('value')->unsigned()->default(0);
            $table->tinyInteger('qualification')->unsigned()->nullable();
            $table->index(['member_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_qualifications');
    }
}
