<?php

namespace App\Component\Payment;

class PaymentRequest
{
    private $MerchantID;
    private $Amount;
    private $Description;
    private $Email;
    private $Mobile;
    private $CallbackURL;

    /**
     * PaymentRequest constructor.
     * @param string $MerchantID
     * @param string $Amount
     * @param string $Description
     * @param string $CallbackURL
     * @param string $mobile
     * @param string $email
     */
    public function __construct($MerchantID, $Amount, $Description, $CallbackURL, $mobile = null, $email = null)
    {
        $this->MerchantID = $MerchantID;
        $this->Amount = $Amount;
        $this->Description = $Description;
        $this->CallbackURL = $CallbackURL;
        $this->Mobile = $mobile;
        $this->Email = $email;
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
     * @return mixed
     */
    public function getDescription()
    {
        return $this->Description;
    }

    /**
     * @param mixed $Description
     */
    public function setDescription($Description): void
    {
        $this->Description = $Description;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->Email;
    }

    /**
     * @param mixed $Email
     */
    public function setEmail($Email): void
    {
        $this->Email = $Email;
    }

    /**
     * @return mixed
     */
    public function getMobile()
    {
        return $this->Mobile;
    }

    /**
     * @param mixed $Mobile
     */
    public function setMobile($Mobile): void
    {
        $this->Mobile = $Mobile;
    }

    /**
     * @return mixed
     */
    public function getCallbackURL()
    {
        return $this->CallbackURL;
    }

    /**
     * @param mixed $CallbackURL
     */
    public function setCallbackURL($CallbackURL): void
    {
        $this->CallbackURL = $CallbackURL;
    }

    /**
     * @return array
     */
    function toArray()
    {
        $array = [
            'MerchantID' => $this->MerchantID,
            'Amount' => $this->Amount,
            'Description' => $this->Description,
            'CallbackURL' => $this->CallbackURL,
        ];
        if ($this->getEmail() != null) {
            $array['Email'] = $this->getEmail();
        }
        if ($this->getMobile() != null) {
            $array['Mobile'] = $this->getMobile();
        }
        return $array;
    }
}
