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
            $table->integer('order')->nullable();
            
            $table->string('type')->nullable()->default(null);
            
            $table->string('name');
            $table->integer('duration');
            $table->integer('min_duration')->nullable()->default(null);
            $table->integer('max_duration')->nullable()->default(null);
            $table->decimal('price', 6,2);
            $table->decimal('deposit', 6,2);

            $table->longtext('disabled_weekdays')->nullable()->default(null);
            $table->longtext('disabled_dates')->nullable()->default(null);
            $table->longtext('json')->nullable()->default(null);
            
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
        Schema::dropIfExists(config('chuckcms-module-booker.services.table'));
    }
}
