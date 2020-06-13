<?php

namespace App\Component\Payment;

class PaymentVerification
{
    private $MerchantID;
    private $Authority;
    private $Amount;

    /**
     * PaymentVerification constructor.
     * @param $MerchantID
     * @param $Authority
     * @param $Amount
     */
    public function __construct($MerchantID, $Authority, $Amount)
    {
        $this->MerchantID = $MerchantID;
        $this->Authority = $Authority;
        $this->Amount = $Amount;
    }

    /**
     * @return mixed
     */
    public function getMerchantID()
    {
        return $this->MerchantID;
    }

    /**
     * @param mixed $MerchantID
     */
    public function setMerchantID($MerchantID): void
    {
        $this->MerchantID = $MerchantID;
    }

    /**
     * @return mixed
     */
    public function getAuthority()
    {
        return $this->Authority;
    }

    /**
     * @param mixed $Authority
     */
    public function setAuthority($Authority): void
    {
        $this->Authority = $Authority;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->Amount;
    }

    /**
     * @param mixed $Amount
     */
    public function setAmount($Amount): void
    {
        $this->Amount = $Amount;
    }

    /**
     * @return array
     */
    function toArray()
    {
        return [
            'MerchantID' => $this->MerchantID,
            'Authority' => $this->Authority,
            'Amount' => $this->Amount,
        ];
    }
}
