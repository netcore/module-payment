<?php

namespace Modules\Payment\Libraries;

use App\Models\User;
use Exception;
use Modules\Payment\Modules\Payment;

class Paysera
{

    private $projectId, $secret, $sandbox;

    public function __construct()
    {
        $this->projectId = config('netcore.module-payment.paysera.projectId');
        $this->secret = config('netcore.module-payment.paysera.secret');
        $this->sandbox = config('netcore.module-payment.paysera.sandbox');
        $this->prefix = config('netcore.module-payment.paysera.order_prefix', '');
    }

    /**
     * @param $user
     * @param $amount
     * @param $country
     * @param $currency
     * @param $successUrl
     * @param $cancelUrl
     * @param $callbackUrl
     * @return \Illuminate\Http\RedirectResponse
     */
    public function makePayment($user, $amount, $country, $currency, $successUrl, $cancelUrl, $callbackUrl)
    {
        try {
            $payment = $user->payments()->create([
                'amount' => $amount,
                'method' => 'creditcard'
            ]);


            $url = WebToPay::redirectToPayment([
                'projectid'     => $this->projectId,
                'sign_password' => $this->secret,
                'orderid'       => $this->prefix . $payment->id,
                'amount'        => ($amount * 100),
                'currency'      => $currency,
                'country'       => $country,
                'accepturl'     => $successUrl,
                'cancelurl'     => $cancelUrl,
                'callbackurl'   => $callbackUrl,
                'test'          => $this->sandbox,
            ], false, true);

            return redirect()->to($url);


        } catch (WebToPayException $e) {

            return redirect()->to($cancelUrl);
        }
    }

    /**
     * @param $data
     * @return array
     */
    public function validateResponse($data)
    {
        try {
            $response = WebToPay::checkResponse($data, array(
                'projectid'     => $this->projectId,
                'sign_password' => $this->secret,
            ));

            \Log::info($response);

            $payment = Payment::find(str_replace($this->prefix, '', $response['orderid']));

            if ($payment && $response['status'] == 1) {
                $payment->status = 'closed';
                $payment->state = 'successful';
                $payment->save();

                return [
                    'type'    => 'success',
                    'payment' => $payment
                ];
            } else {
                if ($payment) {
                    $payment->status = 'closed';
                    $payment->state = 'failed';
                    $payment->save();

                }

                return [
                    'type' => 'failed'
                ];
            }
        } catch (Exception $e) {
            return [
                'type' => 'failed'
            ];
        }
    }

    /**
     * @param $data
     * @return array
     */
    public function validateSmsResponse($data)
    {
        try {
            $response = WebToPay::checkResponse($data, array(
                'projectid'     => $this->projectId,
                'sign_password' => $this->secret,
            ));

            \Log::info($response);

            $user = User::where('phone', $response['from'])->first();

            if ($user) {
                $amount = SMSKeywordByCountry($user->language_is_code)['amount'];

                $user->payments()->create([
                    'amount' => $amount,
                    'state'  => 'successful',
                    'status' => 'closed',
                    'method' => 'sms',
                    'data'   => json_encode($response)
                ]);

                $user->increment('wallet', number_format($amount/100, 0));

                return [
                    'type' => 'success',
                ];
            } else {
                return [
                    'type' => 'failed'
                ];
            }


        } catch (Exception $e) {
            return [
                'type' => 'failed'
            ];
        }
    }
}