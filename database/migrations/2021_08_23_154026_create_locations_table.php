<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('chuckcms-module-booker.locations.table'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->decimal('lat', 8,6);
            $table->decimal('long', 9,6);
            $table->string('google_calendar_id')->nullable();
            $table->longtext('json')->nullable()->default(null);
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
        Schema::dropIfExists(config('chuckcms-module-booker.locations.table'));
    }
}
