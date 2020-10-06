<?php

namespace App\Message;

use App\Entity\Sms as SmsEntity;

class Sms
{
    /**
     * @var SmsEntity
     */
    private $sms;

    /**
     * Sms constructor.
     * @param SmsEntity $sms
     */
    public function __construct(SmsEntity $sms)
    {
        $this->sms = $sms;
    }

    public function getSms(): SmsEntity
    {
        return $this->sms;
    }
}