<?php

Route::group(['middleware' => ['web']], function() {
	Route::group(['middleware' => 'auth'], function () {
        Route::get('/dashboard/booker/appointments', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@appointments')->name('dashboard.module.booker.appointments');
        Route::get('/dashboard/booker/appointment/detail', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@getAppointmentDetail')->name('dashboard.module.booker.appointment.details');
        Route::get('/dashboard/booker/locations', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@locations')->name('dashboard.module.booker.locations');
    });
    // Route::group(['middleware' => 'auth:api'], function(){
    //     Route::get('/dashboard/booker/services', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@getServices')->name('dashboard.module.booker.services');
    // }); 
    Route::get('/dashboard/booker/services', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@getServices')->name('dashboard.module.booker.services');

});


