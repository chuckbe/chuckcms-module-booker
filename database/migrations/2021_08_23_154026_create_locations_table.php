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
            $table->integer('order')->nullable();
            $table->string('name');
            $table->longtext('disabled_weekdays')->nullable()->default(null);
            $table->longtext('disabled_dates')->nullable()->default(null);
            $table->longtext('opening_hours');
            $table->decimal('lat', 8,6)->nullable();
            $table->decimal('long', 9,6)->nullable();
            $table->longtext('json')->nullable()->default(null);
            $table->string('google_calendar_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
