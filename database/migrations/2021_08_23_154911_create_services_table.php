<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('chuckcms-module-booker.services.table'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type');
            $table->string('name');
            $table->string('duration');
            $table->string('min_duration')->nullable()->default(null);
            $table->string('max_duration')->nullable()->default(null);
            $table->decimal('price', 6,2);
            $table->longtext('excluded_days');
            $table->longtext('excluded_dates');
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
        Schema::dropIfExists(config('chuckcms-module-booker.services.table'));
    }
}
