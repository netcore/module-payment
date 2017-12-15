<?php

namespace Modules\Payment\Libraries;

use Requests;

class Paypal
{

    private $clientId, $clientSecret, $url;

    /**
     * Paypal constructor.
     */
    public function __construct()
    {
        $sandbox = config('netcore.module-payment.paypal.sandbox');
        if ($sandbox) {
            $this->clientId = config('netcore.module-payment.paypal.credentials.sandbox.client_id');
            $this->clientSecret = config('netcore.module-payment.paypal.credentials.sandbox.client_secret');
            $this->url = 'https://api.sandbox.paypal.com';
        } else {
            $this->clientId = config('netcore.module-payment.paypal.credentials.live.client_id');
            $this->clientSecret = config('netcore.module-payment.paypal.credentials.live.client_secret');
            $this->url = 'https://api.paypal.com';
        }
    }

    /**
     * @return array
     */
    public function getAccessToken()
    {
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];
        $options = [
            'auth'            => [
                $this->clientId,
                $this->clientSecret

            ],
            'connect-timeout' => 1000, // 1min
            'timeout'         => 1000 // 1min
        ];
        $url = $this->url . '/v1/oauth2/token';
        $data = [
            'grant_type' => 'client_credentials'
        ];


        $request = Requests::post($url, $headers, $data, $options);

        if ($request->status_code != 200) {
            return [
                'type'    => 'error',
                'message' => 'Whoops something went wrong...'
            ];
        }

        $response = json_decode($request->body);

        return [
            'type'  => 'success',
            'token' => $response->access_token
        ];
    }

    /**
     * @param $amount
     * @param $currency
     * @param $successUrl
     * @param $errorUrl
     * @return array
     */
    public function requestPayment($amount, $currency, $successUrl, $errorUrl)
    {
        $getAccessToken = $this->getAccessToken();

        if ($getAccessToken['type'] == 'error') {
            return [
                'type'    => 'error',
                'message' => $getAccessToken['message']
            ];
        }

        $token = $getAccessToken['token'];
        $headers = [
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $options = [
            'connect-timeout' => 1000, // 1min
            'timeout'         => 1000 // 1min
        ];
        $url = $this->url . '/v1/payments/payment';
        $data = [
            'intent'        => 'sale',
            'redirect_urls' => [
                'return_url' => $successUrl,
                'cancel_url' => $errorUrl,
            ],
            'payer'         => [
                'payment_method' => 'paypal'
            ],
            'transactions'  => [
                [
                    'amount' => [
                        'total'    => (string)$amount,
                        'currency' => $currency
                    ]
                ]
            ]
        ];

        $request = Requests::post($url, $headers, json_encode($data), $options);

        $response = json_decode($request->body);

        if (!object_get($response, 'links')) {
            return [
                'type'    => 'error',
                'message' => 'Whoops something went wrong...'
            ];
        }

        $links = collect($response->links);
        $redirectUrl = $links->where('rel', 'approval_url')->first();

        if ($redirectUrl) {
            return [
                'type'     => 'success',
                'redirect' => $redirectUrl->href
            ];
        } else {
            return [
                'type'    => 'error',
                'message' => 'Whoops something went wrong...'
            ];
        }
    }
}