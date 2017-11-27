<?php

namespace Modules\Payment\Repositories;



use Modules\Payment\Libraries\Paypal;
use Modules\Payment\Modules\Payment;

class PaymentRepository
{

    private $config, $paymentMethod, $user, $invoice = false;

    /**
     * ContactRepository constructor.
     * @param null $user
     */
    public function __construct($user = null)
    {
        $this->config = config('netcore.module-payment');
        $this->user = $user;
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
     * @param $data
     * @return $this
     */
    public function withInvoice($data)
    {
        $this->invoice = true;

        invoice()
            ->forUser($data['user'])
            ->setItems($data['items'])
            ->setPaymentDetails($data['method'])
            ->setSender($data['sender_data'])
            ->make();

        return $this;
    }

    /**
     * @param $data
     * @return array
     */
    public function makePayment($data)
    {
        if($this->paymentMethod == 'paypal') {
            return $this->makePaypalPayment($data);
        } else {

        }
    }

    /**
     * @param $data
     * @return array
     */
    private function makePaypalPayment($data)
    {
        $paypal = new Paypal;

        $response = $paypal->requestPayment($data['amount'], $data['currency'], $data['successUrl'], $data['errorUrl']);

        if($response['type'] == 'success' && $this->invoice) {
            $this->makePaymentEntry($data['user'], $data['amount']);
        }

        return $response;
    }

    /**
     * @param $user
     * @param $amount
     */
    private function makePaymentEntry($user, $amount)
    {
        $user->payments()->create([
            'amount' => $amount,
            'state'  => null,
            'method' => 'paypal'
        ]);
    }
}