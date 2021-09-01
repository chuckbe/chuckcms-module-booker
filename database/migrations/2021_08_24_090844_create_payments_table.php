<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('chuckcms-module-booker.payments.table'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('external_id');
            $table->string('type');
            $table->string('status');
            $table->bigInteger('amount');
            $table->longtext('log');
            $table->longtext('json');
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
        Schema::dropIfExists(config('chuckcms-module-booker.payments.table'));
    }
}
