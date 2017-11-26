<?php

namespace Modules\Payment\Repositories;



use Modules\Payment\Libraries\Paypal;
use Modules\Payment\Modules\Payment;

class PaymentRepository
{

    private $config, $paymentMethod;

    /**
     * ContactRepository constructor.
     */
    public function __construct()
    {
        $this->config = config('netcore.module-payment');
    }

    /**
     * @return $this
     */
    public function paypal()
    {
        $this->paymentMethod = 'paypal';

        return $this;
    }

    /**
     * @param $amount
     * @param $currency
     * @param null $successUrl
     * @param null $errorUrl
     * @return array
     */
    public function makePayment($amount, $currency, $successUrl = null, $errorUrl = null)
    {
        if($this->paymentMethod == 'paypal') {
            return $this->makePaypalPayment($amount, $currency, $successUrl, $errorUrl);
        } else {

        }
    }

    /**
     * @param $amount
     * @param $currency
     * @param null $successUrl
     * @param null $errorUrl
     * @return array
     */
    private function makePaypalPayment($amount, $currency, $successUrl = null, $errorUrl = null)
    {
        $paypal = new Paypal;

        $response = $paypal->requestPayment($amount, $currency, $successUrl, $errorUrl);

        return $response;
    }

}