<?php

Route::group(['middleware' => ['web']], function() {
	Route::group(['middleware' => 'auth'], function () {
        Route::get('/dashboard/booker', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@index')->name('dashboard.module.booker.index');
    });
    // Route::group(['middleware' => 'auth:api'], function(){
    //     Route::get('/dashboard/booker/services', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@getServices')->name('dashboard.module.booker.services');
    // }); 
    Route::get('/dashboard/booker/services', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@getServices')->name('dashboard.module.booker.services');

});


