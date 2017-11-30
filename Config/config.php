<?php

return [
    'paypal' => [
        'enabled'           => true,
        'sandbox'           => true,
        'generate_invoices' => true, // Invoice module must be set up
        'credentials' => [
            'sandbox' => [
                'client_id'         => '',
                'client_secret'     => '',
            ],
            'live' => [
                'client_id'         => '',
                'client_secret'     => '',
            ]
        ]

    ],

    'paysera' => [
        'enabled'   => true,
        'sandbox'   => true,
        'projectId' => 0,
        'secret'    => ''
    ]
];
