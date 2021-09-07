<?php

Route::group(['middleware' => ['web']], function() {
	Route::group(['middleware' => 'auth'], function () {
        Route::get('/dashboard/booker/appointments', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@appointments')->name('dashboard.module.booker.appointments');
        Route::get('/dashboard/booker/appointment/detail', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@getAppointmentDetail')->name('dashboard.module.booker.appointment.details');
        
        Route::get('/dashboard/booker/locations', 'Chuckbe\ChuckcmsModuleBooker\Controllers\LocationController@index')->name('dashboard.module.booker.locations.index');
        Route::get('/dashboard/booker/locations/create', 'Chuckbe\ChuckcmsModuleBooker\Controllers\LocationController@create')->name('dashboard.module.booker.locations.create');
        Route::get('/dashboard/booker/locations/{location}/detail', 'Chuckbe\ChuckcmsModuleBooker\Controllers\LocationController@detail')->name('dashboard.module.booker.locations.detail');
        Route::get('/dashboard/booker/locations/{location}/edit', 'Chuckbe\ChuckcmsModuleBooker\Controllers\LocationController@edit')->name('dashboard.module.booker.locations.edit');
        Route::post('/dashboard/booker/locations/save', 'Chuckbe\ChuckcmsModuleBooker\Controllers\LocationController@store')->name('dashboard.module.booker.locations.save');
        Route::post('/dashboard/booker/locations/update', 'Chuckbe\ChuckcmsModuleBooker\Controllers\LocationController@store')->name('dashboard.module.booker.locations.update');
        Route::post('/dashboard/booker/locations/delete', 'Chuckbe\ChuckcmsModuleBooker\Controllers\LocationController@delete')->name('dashboard.module.booker.locations.delete');
        
        Route::get('/dashboard/booker/services', 'Chuckbe\ChuckcmsModuleBooker\Controllers\ServiceController@index')->name('dashboard.module.booker.services.index');
        Route::get('/dashboard/booker/services/create', 'Chuckbe\ChuckcmsModuleBooker\Controllers\ServiceController@create')->name('dashboard.module.booker.services.create');
        Route::get('/dashboard/booker/services/{service}/detail', 'Chuckbe\ChuckcmsModuleBooker\Controllers\ServiceController@detail')->name('dashboard.module.booker.services.detail');
        Route::get('/dashboard/booker/services/{service}/edit', 'Chuckbe\ChuckcmsModuleBooker\Controllers\ServiceController@edit')->name('dashboard.module.booker.services.edit');
        Route::post('/dashboard/booker/services/save', 'Chuckbe\ChuckcmsModuleBooker\Controllers\ServiceController@store')->name('dashboard.module.booker.services.save');
        Route::post('/dashboard/booker/services/update', 'Chuckbe\ChuckcmsModuleBooker\Controllers\ServiceController@store')->name('dashboard.module.booker.services.update');
        Route::post('/dashboard/booker/services/delete', 'Chuckbe\ChuckcmsModuleBooker\Controllers\ServiceController@delete')->name('dashboard.module.booker.services.delete');
    });

    // Route::post('/dashboard/booker/getavailabletimeslots', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@getAvailableTimeslots')->name('dashboard.module.booker.gettimeslots');
    // Route::post('/dashboard/booker/formhandle', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@formHandle')->name('dashboard.module.booker.formhandle');
    
});


