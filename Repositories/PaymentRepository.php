<?php

namespace Modules\Payment\Repositories;



use Modules\Payment\Libraries\Paypal;
use Modules\Payment\Libraries\Paysera;
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
     * @return $this
     */
    public function creditcard()
    {
        $this->paymentMethod = 'creditcard';

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
            return $this->makePayseraPayment($data);
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
            $this->makePaymentEntry($data['user'], $data['amount'], 'paypal');
        }

        return $response;
    }

    /**
     * @param $user
     * @param $amount
     */
    private function makePaymentEntry($user, $amount, $method)
    {
        $user->payments()->create([
            'amount' => $amount,
            'state'  => null,
            'method' => $method
        ]);
    }

    /**
     * @param $data
     * @return \Illuminate\Http\RedirectResponse|void
     */
    private function makePayseraPayment($data)
    {
        $paysera = new Paysera;

        return $paysera->makePayment($data['user'], $data['amount'], $data['country'], $data['currency'], $data['successUrl'], $data['cancelUrl'], $data['callbackUrl']);
    }

    /**
     * @param $request
     * @return array
     */
    public function validatePayment($request)
    {
        $paysera = new Paysera;

        return $paysera->validateResponse($request);
    }
}