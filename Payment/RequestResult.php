<?php

namespace App\Component\Payment;

class RequestResult
{
    /**
     * @var int
     */
    private $status;

    /**
     * @var int
     */
    private $authority;

    /**
     * RequestResult constructor.
     * @param mixed $result
     * @param int $status
     * @param int $authority
     */
    function __construct($result, $status = 0, $authority = 0)
    {
        if (is_null($result)) {
            $this->status = $status;
            $this->authority = $authority;
        } else {
            $this->status = $result->Status;
            $this->authority = (int)$result->Authority;
        }
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function isSuccessful()
    {
        return $this->status == 100;
    }

    /**
     * @return int
     */
    public function getAuthority(): int
    {
        return $this->authority;
    }

    public function __serialize()
    {
        return [
            'status' => $this->status,
            'authority' => $this->authority,
        ];
    }

    public function __toString()
    {
        return json_encode($this->__serialize());
    }
}