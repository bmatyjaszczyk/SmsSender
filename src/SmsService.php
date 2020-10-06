<?php

namespace App;

use App\Entity\Sms;
use App\Entity\User;
use App\Message\Sms as SmsMessage;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class SmsService
{
    /**
     * @var MessageBusInterface
     */
    private $bus;

    /**
     * @var CacheItemPoolInterface
     */
    private $cacheItemPool;

    /**
     * SmsService constructor.
     * @param MessageBusInterface $bus
     * @param CacheItemPoolInterface $cacheItemPool
     */
    public function __construct(MessageBusInterface $bus, CacheItemPoolInterface $cacheItemPool)
    {
        $this->bus = $bus;
        $this->cacheItemPool = $cacheItemPool;
    }

    public function send(Sms $sms, User $user): void
    {
        $lock = $this->cacheItemPool->getItem('lock.' . $user->getId());
        $lock->set(true);
        $lock->expiresAfter(15);
        $this->cacheItemPool->save($lock);

        $this->bus->dispatch(new SmsMessage($sms));
    }

}