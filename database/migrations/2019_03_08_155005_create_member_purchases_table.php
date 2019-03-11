<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_purchases', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->string('external_id')->index();
            $table->uuid('member_uuid')->index();
            $table->uuid('outlet_uuid')->index();
            $table->timestamps();
            $table->double('amount', 10, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_purchases');
    }
}
