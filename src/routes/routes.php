<?php

Route::group(['middleware' => ['web']], function() {
    
    Route::get('/dashboard/booker/appointments/json', 'Chuckbe\ChuckcmsModuleBooker\Controllers\AppointmentController@json')->name('dashboard.module.booker.appointments.json');
    Route::get('/cmb/activate/account/{user_token}', 'Chuckbe\ChuckcmsModuleBooker\Controllers\CustomerController@activate')->name('module.booker.activate.account');
    Route::post('/cmb/activate/account', 'Chuckbe\ChuckcmsModuleBooker\Controllers\CustomerController@activateAccount')->name('module.booker.activate.account.post');

	Route::group(['middleware' => 'auth'], function () {
        Route::get('/dashboard/booker/dashboard', 'Chuckbe\ChuckcmsModuleBooker\Controllers\DashboardController@index')->name('dashboard.module.booker.dashboard');

        Route::get('/dashboard/booker/appointments', 'Chuckbe\ChuckcmsModuleBooker\Controllers\AppointmentController@index')->name('dashboard.module.booker.appointments.index');
        Route::get('/dashboard/booker/appointment/detail', 'Chuckbe\ChuckcmsModuleBooker\Controllers\AppointmentController@detail')->name('dashboard.module.booker.appointments.details');
        Route::post('/dashboard/booker/appointment/create', 'Chuckbe\ChuckcmsModuleBooker\Controllers\AppointmentController@create')->name('dashboard.module.booker.appointments.create');
        Route::post('/dashboard/booker/appointment/modal', 'Chuckbe\ChuckcmsModuleBooker\Controllers\AppointmentController@modal')->name('dashboard.module.booker.appointments.modal');
        Route::post('/dashboard/booker/appointment/cancel', 'Chuckbe\ChuckcmsModuleBooker\Controllers\AppointmentController@cancel')->name('dashboard.module.booker.appointments.cancel');
        Route::post('/dashboard/booker/appointment/status', 'Chuckbe\ChuckcmsModuleBooker\Controllers\AppointmentController@status')->name('dashboard.module.booker.appointments.status');

        Route::get('/dashboard/booker/appointment/{appointment}/invoice', 'Chuckbe\ChuckcmsModuleBooker\Controllers\AppointmentController@invoice')->name('dashboard.module.booker.appointments.invoice');


        //START OF: SUBSCRIPTIONS ROUTES
        Route::get('/dashboard/booker/subscriptions', 'Chuckbe\ChuckcmsModuleBooker\Controllers\SubscriptionController@index')->name('dashboard.module.booker.subscriptions.index');
        Route::post('/dashboard/booker/subscription/save', 'Chuckbe\ChuckcmsModuleBooker\Controllers\SubscriptionController@store')->name('dashboard.module.booker.subscriptions.save');
        Route::get('/dashboard/booker/subscriptions/{subscription}/invoice', 'Chuckbe\ChuckcmsModuleBooker\Controllers\SubscriptionController@invoice')->name('dashboard.module.booker.subscriptions.invoice');
        Route::get('/dashboard/booker/subscriptions/{subscription}/credit_note', 'Chuckbe\ChuckcmsModuleBooker\Controllers\SubscriptionController@creditNote')->name('dashboard.module.booker.subscriptions.credit_note');
        Route::post('/dashboard/booker/subscription/cancel', 'Chuckbe\ChuckcmsModuleBooker\Controllers\SubscriptionController@cancel')->name('dashboard.module.booker.subscriptions.cancel');
        //END OF:   SUBSCRIPTIONS ROUTES
        
        //START OF: SUBSCRIPTION PLANS ROUTES
        Route::get('/dashboard/booker/subscription/plans', 'Chuckbe\ChuckcmsModuleBooker\Controllers\SubscriptionPlanController@index')->name('dashboard.module.booker.subscription_plans.index');
        Route::get('/dashboard/booker/subscription/plans/{subscription_plan}/edit', 'Chuckbe\ChuckcmsModuleBooker\Controllers\SubscriptionPlanController@edit')->name('dashboard.module.booker.subscription_plans.edit');
        Route::post('/dashboard/booker/subscription/plans/save', 'Chuckbe\ChuckcmsModuleBooker\Controllers\SubscriptionPlanController@store')->name('dashboard.module.booker.subscription_plans.save');
        //END OF:   SUBSCRIPTION PLANS ROUTES
        
        //START OF: GIFT CARDS ROUTES
        Route::get('/dashboard/booker/gift-cards', 'Chuckbe\ChuckcmsModuleBooker\Controllers\GiftCardController@index')->name('dashboard.module.booker.gift_cards.index');
        Route::post('/dashboard/booker/gift-cards/save', 'Chuckbe\ChuckcmsModuleBooker\Controllers\GiftCardController@store')->name('dashboard.module.booker.gift_cards.save');
        Route::get('/dashboard/booker/gift-cards/{giftCard}/invoice', 'Chuckbe\ChuckcmsModuleBooker\Controllers\GiftCardController@invoice')->name('dashboard.module.booker.gift_cards.invoice');
        Route::get('/dashboard/booker/gift-cards/{giftCard}/credit_note', 'Chuckbe\ChuckcmsModuleBooker\Controllers\GiftCardController@creditNote')->name('dashboard.module.booker.gift_cards.credit_note');
        //END OF:   GIFT CARDS ROUTES
        
        //START OF: INVOICES ROUTES
        Route::get('/dashboard/booker/invoices', 'Chuckbe\ChuckcmsModuleBooker\Controllers\InvoiceController@index')->name('dashboard.module.booker.invoices.index');
        //END OF:   INVOICES ROUTES
        
        //START OF: CUSTOMERS ROUTES
        Route::get('/dashboard/booker/customers', 'Chuckbe\ChuckcmsModuleBooker\Controllers\CustomerController@index')->name('dashboard.module.booker.customers.index');
        //Route::get('/dashboard/booker/customers/create', 'Chuckbe\ChuckcmsModuleBooker\Controllers\CustomerController@create')->name('dashboard.module.booker.customers.create');
        Route::get('/dashboard/booker/customers/{customer}/detail', 'Chuckbe\ChuckcmsModuleBooker\Controllers\CustomerController@detail')->name('dashboard.module.booker.customers.detail');
        //Route::get('/dashboard/booker/customers/{customer}/edit', 'Chuckbe\ChuckcmsModuleBooker\Controllers\CustomerController@edit')->name('dashboard.module.booker.customers.edit');
        Route::post('/dashboard/booker/customers/save', 'Chuckbe\ChuckcmsModuleBooker\Controllers\CustomerController@store')->name('dashboard.module.booker.customers.save');
        Route::post('/dashboard/booker/customers/update', 'Chuckbe\ChuckcmsModuleBooker\Controllers\CustomerController@store')->name('dashboard.module.booker.customers.update');
        Route::post('/dashboard/booker/customers/address/update', 'Chuckbe\ChuckcmsModuleBooker\Controllers\CustomerController@address')->name('dashboard.module.booker.customers.update_address');
        Route::post('/dashboard/booker/customers/delete', 'Chuckbe\ChuckcmsModuleBooker\Controllers\CustomerController@delete')->name('dashboard.module.booker.customers.delete');
        Route::post('/dashboard/booker/customers/reactivate', 'Chuckbe\ChuckcmsModuleBooker\Controllers\CustomerController@reactivate')->name('dashboard.module.booker.customers.reactivate');
        //END OF:   CUSTOMERS ROUTES

        //START OF: LOCATIONS ROUTES
        Route::get('/dashboard/booker/locations', 'Chuckbe\ChuckcmsModuleBooker\Controllers\LocationController@index')->name('dashboard.module.booker.locations.index');
        Route::get('/dashboard/booker/locations/{location}/edit', 'Chuckbe\ChuckcmsModuleBooker\Controllers\LocationController@edit')->name('dashboard.module.booker.locations.edit');
        Route::post('/dashboard/booker/locations/save', 'Chuckbe\ChuckcmsModuleBooker\Controllers\LocationController@store')->name('dashboard.module.booker.locations.save');
        Route::post('/dashboard/booker/locations/update', 'Chuckbe\ChuckcmsModuleBooker\Controllers\LocationController@store')->name('dashboard.module.booker.locations.update');
        Route::post('/dashboard/booker/locations/delete', 'Chuckbe\ChuckcmsModuleBooker\Controllers\LocationController@delete')->name('dashboard.module.booker.locations.delete');
        //END OF:   LOCATIONS ROUTES
        
        //START OF: SERVICES ROUTES
        Route::get('/dashboard/booker/services', 'Chuckbe\ChuckcmsModuleBooker\Controllers\ServiceController@index')->name('dashboard.module.booker.services.index');
        //Route::get('/dashboard/booker/services/create', 'Chuckbe\ChuckcmsModuleBooker\Controllers\ServiceController@create')->name('dashboard.module.booker.services.create');
        //Route::get('/dashboard/booker/services/{service}/detail', 'Chuckbe\ChuckcmsModuleBooker\Controllers\ServiceController@detail')->name('dashboard.module.booker.services.detail');
        Route::get('/dashboard/booker/services/{service}/edit', 'Chuckbe\ChuckcmsModuleBooker\Controllers\ServiceController@edit')->name('dashboard.module.booker.services.edit');
        Route::post('/dashboard/booker/services/save', 'Chuckbe\ChuckcmsModuleBooker\Controllers\ServiceController@store')->name('dashboard.module.booker.services.save');
        Route::post('/dashboard/booker/services/update', 'Chuckbe\ChuckcmsModuleBooker\Controllers\ServiceController@store')->name('dashboard.module.booker.services.update');
        Route::post('/dashboard/booker/services/delete', 'Chuckbe\ChuckcmsModuleBooker\Controllers\ServiceController@delete')->name('dashboard.module.booker.services.delete');
        //END OF:   SERVICES ROUTES
        
        //START OF: SETTINGS ROUTES
        Route::get('/dashboard/booker/settings', 'Chuckbe\ChuckcmsModuleBooker\Controllers\SettingsController@index')->name('dashboard.module.booker.settings.index');
        Route::post('/dashboard/booker/settings/update', 'Chuckbe\ChuckcmsModuleBooker\Controllers\Settings\GeneralController@update')->name('dashboard.module.booker.settings.index.general.update');

        Route::get('/dashboard/booker/settings/appointments', 'Chuckbe\ChuckcmsModuleBooker\Controllers\SettingsController@appointments')->name('dashboard.module.booker.settings.index.appointments');
        Route::post('/dashboard/booker/settings/appointments/update', 'Chuckbe\ChuckcmsModuleBooker\Controllers\Settings\AppointmentController@update')->name('dashboard.module.booker.settings.index.appointments.update');

        Route::get('/dashboard/booker/settings/statuses/edit/{status}', 'Chuckbe\ChuckcmsModuleBooker\Controllers\Settings\StatusController@edit')->name('dashboard.module.booker.settings.index.statuses.edit');
        Route::get('/dashboard/booker/settings/statuses/{status}/emails/new', 'Chuckbe\ChuckcmsModuleBooker\Controllers\Settings\StatusController@email')->name('dashboard.module.booker.settings.index.statuses.email.new');
        Route::post('/dashboard/booker/settings/statuses/update', 'Chuckbe\ChuckcmsModuleBooker\Controllers\Settings\StatusController@update')->name('dashboard.module.booker.settings.index.statuses.update');
        Route::post('/dashboard/booker/settings/statuses/emails/save', 'Chuckbe\ChuckcmsModuleBooker\Controllers\Settings\StatusController@emailSave')->name('dashboard.module.booker.settings.index.statuses.email.save');
		Route::post('/dashboard/booker/settings/statuses/emails/delete', 'Chuckbe\ChuckcmsModuleBooker\Controllers\Settings\StatusController@emailDelete')->name('dashboard.module.booker.settings.index.statuses.email.delete');

        Route::get('/dashboard/booker/settings/subscriptions', 'Chuckbe\ChuckcmsModuleBooker\Controllers\SettingsController@subscriptions')->name('dashboard.module.booker.settings.index.subscriptions');

        Route::get('/dashboard/booker/settings/subscriptions/statuses/edit/{status}', 'Chuckbe\ChuckcmsModuleBooker\Controllers\Settings\SubscriptionStatusController@edit')->name('dashboard.module.booker.settings.index.subscriptions.statuses.edit');
        Route::get('/dashboard/booker/settings/subscriptions/statuses/{status}/emails/new', 'Chuckbe\ChuckcmsModuleBooker\Controllers\Settings\SubscriptionStatusController@email')->name('dashboard.module.booker.settings.index.subscriptions.statuses.email.new');
        Route::post('/dashboard/booker/settings/subscriptions/statuses/update', 'Chuckbe\ChuckcmsModuleBooker\Controllers\Settings\SubscriptionStatusController@update')->name('dashboard.module.booker.settings.index.subscriptions.statuses.update');
        Route::post('/dashboard/booker/settings/subscriptions/statuses/emails/save', 'Chuckbe\ChuckcmsModuleBooker\Controllers\Settings\SubscriptionStatusController@emailSave')->name('dashboard.module.booker.settings.index.subscriptions.statuses.email.save');
        Route::post('/dashboard/booker/settings/subscriptions/statuses/emails/delete', 'Chuckbe\ChuckcmsModuleBooker\Controllers\Settings\SubscriptionStatusController@emailDelete')->name('dashboard.module.booker.settings.index.subscriptions.statuses.email.delete');
			
        Route::get('/dashboard/booker/settings/customer', 'Chuckbe\ChuckcmsModuleBooker\Controllers\SettingsController@customer')->name('dashboard.module.booker.settings.index.customer');
        Route::post('/dashboard/booker/settings/customer/update', 'Chuckbe\ChuckcmsModuleBooker\Controllers\\Settings\CustomerController@update')->name('dashboard.module.booker.settings.index.customer.update');
        
        Route::get('/dashboard/booker/settings/integrations', 'Chuckbe\ChuckcmsModuleBooker\Controllers\SettingsController@integrations')->name('dashboard.module.booker.settings.index.integrations');
        Route::post('/dashboard/booker/settings/integrations/update', 'Chuckbe\ChuckcmsModuleBooker\Controllers\Settings\IntegrationsController@update')->name('dashboard.module.booker.settings.index.integrations.update');
        //END OF:   SETTINGS ROUTES
    });

    Route::post('/module/booker/get-available-dates', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@getAvailableDates')->name('module.booker.get_available_dates');
    Route::post('/module/booker/book', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@makeAppointment')->name('module.booker.book');

    Route::post('/module/booker/subscribe', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@makeSubscription')->name('module.booker.subscribe');

    Route::get('/cmb/follow-up/{appointment}/redirect', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@followup')->name('module.booker.checkout.followup');
    Route::get('/cmb/follow-up/{appointment}/retry-payment', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@retryPayment')->name('module.booker.checkout.retry_payment');
    Route::get('/cmb/follow-up/{subscription}/payment', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@subscriptionPayment')->name('module.booker.checkout.subscription_payment');

    Route::get('/cmb/subscription/follow-up/{subscription}/redirect', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@subscriptionFollowup')->name('module.booker.checkout.subscription.followup');
});

Route::post('/webhook/chuck-booker-module', 'Chuckbe\ChuckcmsModuleBooker\Controllers\BookerController@webhookMollie')->name('module.booker.mollie_webhook');
