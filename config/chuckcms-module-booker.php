<?php

return [
    'locations' => [
		'table' => 'cmb_locations',
		'weekdays' => [
			'1' => 'Monday',
			'2' => 'Tuesday',
			'3' => 'Wednesday',
			'4' => 'Thursday',
			'5' => 'Friday',
			'6' => 'Saturday',
			'0' => 'Sunday',
		]
	],

    'services' => [
		'table' => 'cmb_services'
	],

    'appointments' => [
		'table' => 'cmb_appointments'
	],

    'payments' => [
		'table' => 'cmb_payments'
	],
	
	'customers' => [
		'table' => 'cmb_customers'
	],

	'users' => [
		'table' => 'users',
		'model' => Chuckbe\Chuckcms\Models\User::class
	],
];
