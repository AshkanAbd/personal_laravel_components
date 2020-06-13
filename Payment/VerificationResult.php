<?php

namespace App\Component\Payment;

class VerificationResult
{
    /**
     * @var int
     */
    private $status;

    /**
     * @var integer
     */
    private $refId;

    /**
     * VerificationResult constructor.
     * @param mixed $result
     * @param int $status
     * @param int $refId
     */
    public function __construct($result, $status = 0, $refId = 0)
    {
        if (is_null($result)) {
            $this->status = $status;
            $this->refId = $refId;
        } else {
            $this->status = $result->Status;
            $this->refId = $result->RefID;
        }
    }


    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getRefId(): int
    {
        return $this->refId;
    }

    public function isSuccessful()
    {
        return $this->status == 100 || $this->status == 101;
    }

    public function isDoneBefore()
    {
        return $this->status == 101;
    }

    public function __serialize(): array
    {
        return [
            'status' => $this->status,
            'ref_id' => $this->refId,
        ];
    }
}
