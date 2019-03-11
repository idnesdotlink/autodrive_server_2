<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberChildrenQualificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_children_qualifications', function (Blueprint $table) {
            $table->uuid('member_uuid')->index();
            $level = collect(config('level'));
            $level->each(function($item) use(&$table) {
                $level_name = (string) $item['id'];
                $table->integer($level_name)->unsigned()->default(0);
                $table->index(['member_uuid', $level_name]);
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_children_qualifications');
    }
}
