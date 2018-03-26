<?php

return [
    /**
     * --------------------------------------------------------
     * PayPal config.
     * --------------------------------------------------------
     */
    'paypal'     => [
        'enabled'           => true,
        'sandbox'           => true,
        'generate_invoices' => true, // Invoice module must be set up
        'credentials'       => [
            'sandbox' => [
                'client_id'     => '',
                'client_secret' => '',
            ],
            'live'    => [
                'client_id'     => '',
                'client_secret' => '',
            ],
        ],
    ],

    /**
     * --------------------------------------------------------
     * Paysera config.
     * --------------------------------------------------------
     */
    'paysera'    => [
        'enabled'   => true,
        'sandbox'   => true,
        'projectId' => 0,
        'secret'    => '',
    ],

    /**
     * --------------------------------------------------------
     * Braintree config.
     * --------------------------------------------------------
     */
    'braintree'  => [
        'enabled'     => true,
        'environment' => env('APP_ENV') == 'production' ? 'production' : 'sandbox',
        'merchant_id' => '',
        'public_key'  => '',
        'private_key' => '',
    ],

    /**
     * --------------------------------------------------------
     * Admin side payments table config.
     * --------------------------------------------------------
     */
    'datatables' => [
        'name_column' => 'first_name',
        'user_route'  => 'user::users.edit',
    ],
];
