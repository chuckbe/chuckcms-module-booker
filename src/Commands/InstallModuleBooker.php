<?php

namespace Chuckbe\ChuckcmsModuleBooker\Commands;

use Chuckbe\Chuckcms\Chuck\ModuleRepository;
use Illuminate\Console\Command;

class InstallModuleBooker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chuckcms-module-booker:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command installs the ChuckCMS Booker Module .';

    /**
     * The module repository implementation.
     *
     * @var ModuleRepository
     */
    protected $moduleRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ModuleRepository $moduleRepository)
    {
        parent::__construct();

        $this->moduleRepository = $moduleRepository;
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = 'ChuckCMS Booker Module';
        $slug = 'chuckcms-module-booker';
        $hintpath = 'chuckcms-module-booker';
        $path = 'chuckbe/chuckcms-module-booker';
        $type = 'module';
        $version = '0.0.1';
        $author = 'Karel Brijs (karel@chuck.be)';

        $json = [];
        $json['admin']['show_in_menu'] = true;


        $json['admin']['menu'] = array(
            'name' => 'Booker',
            'icon' => "calender",
            'route' => '#',
            'has_submenu' => true,
            'submenu' => array(
                'a' => array(
                    'name' => 'Afspraken',
                    'route' => 'dashboard.module.booker.appointments.index',
                    'has_submenu' => false,
                    'submenu' => null
                ),
                'b' => array(
                    'name' => 'Klanten',
                    'route' => 'dashboard.module.booker.customers.index',
                    'has_submenu' => false,
                    'submenu' => null
                ),
                'c' => array(
                    'name' => 'Locaties',
                    'route' => 'dashboard.module.booker.locations.index',
                    'has_submenu' => false,
                    'submenu' => null
                ),
                'd' => array(
                    'name' => 'Diensten',
                    'route' => 'dashboard.module.booker.services.index',
                    'has_submenu' => false,
                    'submenu' => null
                ),
                'e' => array(
                    'name' => 'Instellingen',
                    'route' => 'dashboard.module.booker.settings.index',
                    'has_submenu' => false,
                    'submenu' => null
                ),
            )
        );
        $json['admin']['settings'] = [];
        $json['admin']['settings']['appointment'] = [
            'can_guest_checkout' => true,
            'title' => 'string',
        ];
        $json['admin']['settings']['appointment']['statuses'] = [
            'new' => [
                'display_name' => ['nl' => 'Nieuwe afspraak', 'en' => 'New appointment'],
                'short' => ['nl' => 'Nieuw', 'en' => 'New'],
                'send_email' => false,
                'email' => [],
                'invoice' => false,
                'delivery' => false,
                'paid' => false
            ],
            'awaiting' => [
                'display_name' => ['nl' => 'In afwachting van betaling', 'en' => 'Awaiting payment'],
                'short' => ['nl' => 'Afwachting', 'en' => 'Awaiting'],
                'send_email' => false,
                'email' => [],
                'invoice' => false,
                'delivery' => false,
                'paid' => false
            ],
            'canceled' => [
                'display_name' => ['nl' => 'Bestelling geannuleerd', 'en' => 'Order canceled'],
                'short' => ['nl' => 'Geannuleerd', 'en' => 'Canceled'],
                'send_email' => true,
                'email' => [
                    'customer' => [
                        'to' => '[%ORDER_EMAIL%]',
                        'to_name' => '[%ORDER_SURNAME%] [%ORDER_NAME%]',
                        'cc' => null,
                        'bcc' => null,
                        'template' => 'chuckcms-module-ecommerce::emails.default',
                        'logo' => true,
                        'send_delivery_note' => false,
                        'data' => [
                            'subject' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw bestelling #[%ORDER_NUMBER%] werd geannuleerd',
                                    'en' => 'Your order #[%ORDER_NUMBER%] was canceled'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'hidden_preheader' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw bestelling met bestelnummer #[%ORDER_NUMBER%] werd geannuleerd. In deze mail vindt u meer informatie terug.',
                                    'en' => 'Your order #[%ORDER_NUMBER%] was canceled. You can find more information in this email.'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'intro' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Beste [%ORDER_SURNAME%] [%ORDER_NAME%]<br><br>Uw bestelling werd geannuleerd. Indien u reeds heeft betaald wordt dit bedrag automatisch teruggestort op het rekeningnummer dat gelinkt is aan de kaart waarmee u de betaling heeft uitgevoerd. <br><br> Denkt u dat het niet de bedoeling dat deze bestelling geannuleerd werd? Geen zorgen, neem dan contact op met de klantendienst.',
                                    'en' => 'Dear [%ORDER_SURNAME%] [%ORDER_NAME%]<br><br>Your order was canceled. If you have already paid then we will automatically debit the account linked to the card that has been used to pay this order. <br><br>Do you think this order was not supposed to be canceled? No worries, contact our customer support.'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'body_title' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw Bestelling',
                                    'en' => 'Your Order'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'body' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Hieronder vind je nogmaals een overzicht terug van jouw bestelling. <br><br> <b>Verzending:</b> [%ORDER_CARRIER_NAME%] <br> <b>Verzendtijd:</b> [%ORDER_CARRIER_TRANSIT_TIME%] <br><br> <b>Overzicht: </b> <br> [%ORDER_PRODUCTS%] <br> <b>Verzendkosten</b>: [%ORDER_SHIPPING_TOTAL%] <br><br> <b>Totaal</b>: [%ORDER_FINAL%] <br><br> <b>Facturatie adres: </b> <br> Naam: [%ORDER_SURNAME%] [%ORDER_NAME%] <br> E-mail: [%ORDER_EMAIL%] <br> Tel: [%ORDER_TELEPHONE%] <br> Bedrijf: [%ORDER_COMPANY%] <br> BTW: [%ORDER_COMPANY_VAT%] <br> Adres: <br>[%ORDER_BILLING_STREET%] [%ORDER_BILLING_HOUSENUMBER%], <br>[%ORDER_BILLING_POSTALCODE%] [%ORDER_BILLING_CITY%], [%ORDER_BILLING_COUNTRY%] <br><br> <b>Verzendadres:</b><br>Naam: [%ORDER_SURNAME%] [%ORDER_NAME%] <br>Adres:<br>[%ORDER_SHIPPING_STREET%] [%ORDER_SHIPPING_HOUSENUMBER%], <br>[%ORDER_SHIPPING_POSTALCODE%] [%ORDER_SHIPPING_CITY%], [%ORDER_SHIPPING_COUNTRY%]',
                                    'en' => 'Below you will find another summary of your order. <br><br> <b>Shipping:</b> [%ORDER_CARRIER_NAME%] <br> <b>Transit time:</b> [%ORDER_CARRIER_TRANSIT_TIME%] <br><br> <b>Order: </b> <br> [%ORDER_PRODUCTS%] <br> <b>Shipping fees</b>: [%ORDER_SHIPPING_TOTAL%] <br><br> <b>Total</b>: [%ORDER_FINAL%] <br><br> <b>Invoice address: </b> <br> Name: [%ORDER_SURNAME%] [%ORDER_NAME%] <br> E-mail: [%ORDER_EMAIL%] <br> Tel: [%ORDER_TELEPHONE%] <br> Company: [%ORDER_COMPANY%] <br> VAT: [%ORDER_COMPANY_VAT%] <br> Address: <br>[%ORDER_BILLING_STREET%] [%ORDER_BILLING_HOUSENUMBER%], <br>[%ORDER_BILLING_POSTALCODE%] [%ORDER_BILLING_CITY%], [%ORDER_BILLING_COUNTRY%] <br><br> <b>Shipping address:</b><br>Name: [%ORDER_SURNAME%] [%ORDER_NAME%] <br>Address:<br>[%ORDER_SHIPPING_STREET%] [%ORDER_SHIPPING_HOUSENUMBER%], <br>[%ORDER_SHIPPING_POSTALCODE%] [%ORDER_SHIPPING_CITY%], [%ORDER_SHIPPING_COUNTRY%]'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'footer' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Heeft u vragen over uw bestelling? U kan ons steeds contacteren.<br><br><a href="mailto:' . config('chuckcms-module-ecommerce.company.email') . '">' . config('chuckcms-module-ecommerce.company.email') . '</a><br><br>' . config('chuckcms-module-ecommerce.company.name'),
                                    'en' => 'Do you have any other questions about this order? You can always reach us.<br><br><a href="mailto:' . config('chuckcms-module-ecommerce.company.email') . '">' . config('chuckcms-module-ecommerce.company.email') . '</a><br><br>' . config('chuckcms-module-ecommerce.company.name')
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                        ]
                    ]
                ],
                'invoice' => false,
                'delivery' => false,
                'paid' => false
            ],
            'error' => [
                'display_name' => ['nl' => 'Betalingsfout', 'en' => 'Payment Error'],
                'short' => ['nl' => 'Betalingsfout', 'en' => 'Payment Error'],
                'send_email' => true,
                'email' => [
                    'customer' => [
                        'to' => '[%ORDER_EMAIL%]',
                        'to_name' => '[%ORDER_SURNAME%] [%ORDER_NAME%]',
                        'cc' => null,
                        'bcc' => null,
                        'template' => 'chuckcms-module-ecommerce::emails.default',
                        'logo' => true,
                        'send_delivery_note' => false,
                        'data' => [
                            'subject' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw bestelling #[%ORDER_NUMBER%] is mislukt',
                                    'en' => 'Your order #[%ORDER_NUMBER%] has failed'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'hidden_preheader' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw bestelling met bestelnummer #[%ORDER_NUMBER%] is mislukt. In deze mail vindt u meer informatie over uw bestelling terug.',
                                    'en' => 'Your order #[%ORDER_NUMBER%] has failed. In this e-mail you will find more information on your order.'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'intro' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Beste [%ORDER_SURNAME%] [%ORDER_NAME%]<br><br>Uw bestelling is mislukt. Helaas is er iets misgegaan met de betaling. Heeft u nog vragen? Neem gerust contact met ons op.',
                                    'en' => 'Dear [%ORDER_SURNAME%] [%ORDER_NAME%]<br><br>Your order has failed. Unfortunately something went wrong with your payment. Do you have any other questions? Please don\'t hesitate to contact us.'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'body_title' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw Bestelling',
                                    'en' => 'Your Order'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'body' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Hieronder vind je nogmaals een overzicht terug van jouw bestelling. <br><br> [%ORDER_PRODUCTS%] <br> <b>Verzendkosten</b>: [%ORDER_SHIPPING_TOTAL%] <br><br> <b>Totaal</b>: [%ORDER_FINAL%] <br><br> <b>Facturatie adres: </b> <br> Naam: [%ORDER_SURNAME%] [%ORDER_NAME%] <br> E-mail: [%ORDER_EMAIL%] <br> Tel: [%ORDER_TELEPHONE%] <br> Bedrijf: [%ORDER_COMPANY%] <br> BTW: [%ORDER_COMPANY_VAT%] <br> Adres: <br>[%ORDER_BILLING_STREET%] [%ORDER_BILLING_HOUSENUMBER%], <br>[%ORDER_BILLING_POSTALCODE%] [%ORDER_BILLING_CITY%], [%ORDER_BILLING_COUNTRY%] <br><br> <b>Verzendadres:</b><br>Naam: [%ORDER_SURNAME%] [%ORDER_NAME%] <br>Adres:<br>[%ORDER_SHIPPING_STREET%] [%ORDER_SHIPPING_HOUSENUMBER%], <br>[%ORDER_SHIPPING_POSTALCODE%] [%ORDER_SHIPPING_CITY%], [%ORDER_SHIPPING_COUNTRY%]',
                                    'en' => 'Below you will find an overview of your order. <br><br> [%ORDER_PRODUCTS%] <br> <b>Shipping costs</b>: [%ORDER_SHIPPING_TOTAL%] <br><br> <b>Total</b>: [%ORDER_FINAL%] <br><br> <b>Invoice address: </b> <br> Name: [%ORDER_SURNAME%] [%ORDER_NAME%] <br> E-mail: [%ORDER_EMAIL%] <br> Tel: [%ORDER_TELEPHONE%] <br> Company: [%ORDER_COMPANY%] <br> VAT: [%ORDER_COMPANY_VAT%] <br> Address: <br>[%ORDER_BILLING_STREET%] [%ORDER_BILLING_HOUSENUMBER%], <br>[%ORDER_BILLING_POSTALCODE%] [%ORDER_BILLING_CITY%], [%ORDER_BILLING_COUNTRY%] <br><br> <b>Shipping address:</b><br>Name: [%ORDER_SURNAME%] [%ORDER_NAME%] <br>Address:<br>[%ORDER_SHIPPING_STREET%] [%ORDER_SHIPPING_HOUSENUMBER%], <br>[%ORDER_SHIPPING_POSTALCODE%] [%ORDER_SHIPPING_CITY%], [%ORDER_SHIPPING_COUNTRY%]'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'footer' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Heeft u vragen over uw bestelling? U kan ons steeds contacteren.<br><br><a href="mailto:' . config('chuckcms-module-ecommerce.company.email') . '">' . config('chuckcms-module-ecommerce.company.email') . '</a><br><br>' . config('chuckcms-module-ecommerce.company.name'),
                                    'en' => 'Your order is shipped and on its way to you.'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                        ]
                    ]
                ],
                'invoice' => false,
                'delivery' => false,
                'paid' => false
            ],
            'payment' => [
                'display_name' => ['nl' => 'Betaald', 'en' => 'Paid'],
                'short' => ['nl' => 'Betaald', 'en' => 'Paid'],
                'send_email' => true,
                'email' => [
                    'customer' => [
                        'to' => '[%ORDER_EMAIL%]',
                        'to_name' => '[%ORDER_SURNAME%] [%ORDER_NAME%]',
                        'cc' => null,
                        'bcc' => null,
                        'template' => 'chuckcms-module-ecommerce::emails.default',
                        'logo' => true,
                        'send_delivery_note' => false,
                        'data' => [
                            'subject' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw bestelling #[%ORDER_NUMBER%] is verzonden',
                                    'en' => 'Your order #[%ORDER_NUMBER%] was shipped'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'hidden_preheader' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw bestelling met bestelnummer #[%ORDER_NUMBER%] is onderweg. In deze mail vindt u meer informatie over uw bestelling terug.',
                                    'en' => 'Your order #[%ORDER_NUMBER%] was shipped'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'intro' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Beste [%ORDER_SURNAME%] [%ORDER_NAME%]<br><br>Uw bestelling is onderweg. Uw bestelling wordt volgende werkdag geleverd tussen 9:00u en 19:00u. Is er niemand thuis? Dan proberen we het de dag erna nog eens, maak u geen zorgen. Heeft u nog vragen? Neem gerust contact met ons op.',
                                    'en' => 'Order is shipped'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'body_title' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw Bestelling',
                                    'en' => 'Your Order'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'body' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Hieronder vind je nogmaals een overzicht terug van jouw bestelling. <br><br> <b>Verzending:</b> [%ORDER_CARRIER_NAME%] <br> <b>Verzendtijd:</b> [%ORDER_CARRIER_TRANSIT_TIME%] <br><br> <b>Overzicht: </b> <br> [%ORDER_PRODUCTS%] <br> <b>Verzendkosten</b>: [%ORDER_SHIPPING_TOTAL%] <br><br> <b>Totaal</b>: [%ORDER_FINAL%] <br><br> <b>Facturatie adres: </b> <br> Naam: [%ORDER_SURNAME%] [%ORDER_NAME%] <br> E-mail: [%ORDER_EMAIL%] <br> Tel: [%ORDER_TELEPHONE%] <br> Bedrijf: [%ORDER_COMPANY%] <br> BTW: [%ORDER_COMPANY_VAT%] <br> Adres: <br>[%ORDER_BILLING_STREET%] [%ORDER_BILLING_HOUSENUMBER%], <br>[%ORDER_BILLING_POSTALCODE%] [%ORDER_BILLING_CITY%], [%ORDER_BILLING_COUNTRY%] <br><br> <b>Verzendadres:</b><br>Naam: [%ORDER_SURNAME%] [%ORDER_NAME%] <br>Adres:<br>[%ORDER_SHIPPING_STREET%] [%ORDER_SHIPPING_HOUSENUMBER%], <br>[%ORDER_SHIPPING_POSTALCODE%] [%ORDER_SHIPPING_CITY%], [%ORDER_SHIPPING_COUNTRY%]',
                                    'en' => 'Your order is shipped and on its way to you.'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'footer' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Heeft u vragen over uw bestelling? U kan ons steeds contacteren.<br><br><a href="mailto:' . config('chuckcms-module-ecommerce.company.email') . '">' . config('chuckcms-module-ecommerce.company.email') . '</a><br><br>' . config('chuckcms-module-ecommerce.company.name'),
                                    'en' => 'Your order is shipped and on its way to you.'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                        ]
                    ]
                ],
                'invoice' => true,
                'delivery' => false,
                'paid' => true
            ]
        ];
        $json['admin']['settings']['customer'] = [
            'is_tel_required' => true,
            'title' => 'string',
        ];
        $json['admin']['settings']['integrations']['mollie'] = [];
        $json['admin']['settings']['integrations']['mollie']['key'] = null;
        $json['admin']['settings']['integrations']['mollie']['methods'] = ['bancontact', 'belfius', 'creditcard', 'ideal', 'inghomepay', 'kbc', 'paypal'];

        // create the module
        $module = $this->moduleRepository->createFromArray([
            'name' => $name,
            'slug' => $slug,
            'hintpath' => $hintpath,
            'path' => $path,
            'type' => $type,
            'version' => $version,
            'author' => $author,
            'json' => $json
        ]);


        $this->info('.         .');
        $this->info('..         ..');
        $this->info('...         ...');
        $this->info('.... AWESOME ....');
        $this->info('...         ...');
        $this->info('..         ..');
        $this->info('.         .');
        $this->info('.         .');
        $this->info('..         ..');
        $this->info('...         ...');
        $this->info('....   JOB   ....');
        $this->info('...         ...');
        $this->info('..         ..');
        $this->info('.         .');
        $this->info(' ');
        $this->info('Module installed: ChuckCMS Booker Module');
        $this->info(' ');
    }
    
}
