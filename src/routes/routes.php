<?php

Route::group(['middleware' => ['web']], function() {
	Route::group(['middleware' => 'auth'], function () {
        Route::get('/dashboard/booker/appointments', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@appointments')->name('dashboard.module.booker.appointments');
        Route::get('/dashboard/booker/appointment/detail', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@getAppointmentDetail')->name('dashboard.module.booker.appointment.details');
        Route::get('/dashboard/booker/locations', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@locations')->name('dashboard.module.booker.locations');
        Route::get('/dashboard/booker/locations/{location_id}-edit', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@editLocation')->name('dashboard.module.booker.location.edit');
        Route::post('/dashboard/booker/locations/save', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@saveLocation')->name('dashboard.module.booker.location.save');
        Route::post('/dashboard/booker/createlocation', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@createLocation')->name('dashboard.module.booker.createlocation');
        Route::post('/dashboard/booker/deletelocation', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@deleteLocation')->name('dashboard.module.booker.deletelocation');
    });
    // Route::group(['middleware' => 'auth:api'], function(){
    //     Route::get('/dashboard/booker/services', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@getServices')->name('dashboard.module.booker.services');
    // }); 
    Route::get('/dashboard/booker/getservices', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@getServices')->name('dashboard.module.booker.getservices');
    Route::get('/dashboard/booker/getlocations', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@getLocations')->name('dashboard.module.booker.getlocations');
    Route::post('/dashboard/booker/getavailabletimeslots', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@getAvailableTimeslots')->name('dashboard.module.booker.gettimeslots');
    Route::post('/dashboard/booker/formhandle', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@formHandle')->name('dashboard.module.booker.formhandle');
    
});


