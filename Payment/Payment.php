<?php

namespace App\Component\Payment;

use \SoapClient;
use SoapFault;

class Payment
{
    /**
     * @param PaymentRequest $paymentRequest
     * @return RequestResult
     * @throws SoapFault
     */
    public static function sendToPayment(PaymentRequest $paymentRequest)
    {
        $client = new SoapClient(components('PAYMENT_REQUEST'), ['encoding' => 'UTF-8']);
        return new RequestResult($client->PaymentRequest($paymentRequest->toArray()));
    }

    /**
     * @param PaymentVerification $paymentVerification
     * @return VerificationResult
     * @throws SoapFault
     */
    public static function verifyPayment(PaymentVerification $paymentVerification)
    {
        $client = new SoapClient(components('PAYMENT_VERIFICATION'), ['encoding' => 'UTF-8']);
        return new VerificationResult($client->PaymentVerification($paymentVerification->toArray()));
    }
}
