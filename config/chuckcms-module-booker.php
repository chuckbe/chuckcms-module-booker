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

    'subscription_plans' => [
        'table' => 'cmb_subscription_plans'
    ],

    'subscriptions' => [
        'table' => 'cmb_subscriptions'
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

	'auth' => [
		'template' => [
			'hintpath' => 'chuckcms-template-starter',
            'login_blade' => 'account.auth',
            'registration_blade' => 'account.register',
            'activation_blade' => 'account.activate',
		],
	],

    'followup' => [
        'appointment' => '/bedankt-afspraak',
        'subscription' => '/bedankt-abo'
    ],

    'emails' => [
        'from_email' => 'hello@chuck.be',
        'from_name' => 'ChuckCMS Appointments',
    ],

    'integrations' => [
        'mollie' => [
            'methods' => [
                'applepay' => [
                    'display_name' => 'Apple Pay',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/applepay@2x.png'
                ],
                'bancontact' => [
                    'display_name' => 'Bancontact (mistercash)',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/bancontact@2x.png'
                ],
                'banktransfer' => [
                    'display_name' => 'Banktransfer',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/banktransfer@2x.png'
                ],
                'belfius' => [
                    'display_name' => 'Belfius',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/belfius@2x.png'
                ],
                'creditcard' => [
                    'display_name' => 'Creditcard (Visa, MasterCard, Amex)',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/creditcard@2x.png'
                ],
                'directdebit' => [
                    'display_name' => 'Direct Debit (SEPA)',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/directdebit@2x.png'
                ],
                'eps' => [
                    'display_name' => 'EPS',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/eps@2x.png'
                ],
                'giftcard' => [
                    'display_name' => 'Giftcard',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/giftcard@2x.png'
                ],
                'giropay' => [
                    'display_name' => 'Giropay',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/giropay@2x.png'
                ],
                'ideal' => [
                    'display_name' => 'Ideal',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/ideal@2x.png'
                ],
                'inghomepay' => [
                    'display_name' => 'ING',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/inghomepay@2x.png'
                ],
                'kbc' => [
                    'display_name' => 'KBC',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/kbc@2x.png'
                ],
                'klarna' => [
                    'display_name' => 'Klarna',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/klarna@2x.png'
                ],
                'mybank' => [
                    'display_name' => 'Mybank',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/mybank@2x.png'
                ],
                'paypal' => [
                    'display_name' => 'Paypal',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/paypal@2x.png'
                ],
                'paysafecard' => [
                    'display_name' => 'Paysafecard',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/paysafecard@2x.png'
                ],
                'przelewy24' => [
                    'display_name' => 'Przelewy24',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/przelewy24@2x.png'
                ],
                'sofort' => [
                    'display_name' => 'Sofort',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/sofort@2x.png'
                ]
            ]
        ]
    ],

    'currencies' => [
        'AUD' => '$ Australia Dollar',
        'EUR' => '€ Euro',
        'JPY' => '¥ Japan Yen',
        'GBP' => '£ United Kingdom Pound',
        'USD' => '$ United States Dollar'
    ],
];
