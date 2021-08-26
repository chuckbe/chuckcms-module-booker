<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('chuckcms-module-booker.appointments.table'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('location_id');
            $table->integer('client_id');
            $table->string('date');
            $table->string('time');
            $table->string('duration');
            $table->string('status');
            $table->string('is_cancelled');
            $table->decimal('price', 6,2);
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
        Schema::dropIfExists(config('chuckcms-module-booker.appointments.table'));
    }
}
