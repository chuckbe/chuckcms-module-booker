<?php

Route::group(['middleware' => ['web']], function() {
	Route::group(['middleware' => 'auth'], function () {
        Route::get('/dashboard/booker', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@index')->name('dashboard.module.booker.index');
    });
});
