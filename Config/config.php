<?php

return [
    'paypal' => [
        'enabled'           => true,
        'sandbox'           => true,
        'generate_invoices' => true, // Invoice module must be set up
        'client_id'         => '',
        'client_secret'     => '',
    ],

    'paysera' => [
        'enabled'   => true,
        'sandbox'   => true,
        'projectId' => 0,
        'secret'    => ''
    ]
];
