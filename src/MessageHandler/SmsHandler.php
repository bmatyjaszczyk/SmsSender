<?php

namespace App\MessageHandler;

use App\Entity\SmsStatus;
use App\Entity\User;
use App\Message\Sms;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Twilio\Rest\Client;

class SmsHandler implements MessageHandlerInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $fromNumber;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var string
     */
    private $statusCallback;

    /**
     * SmsHandler constructor.
     * @param Client $client
     * @param string $fromNumber
     * @param string $statusCallback
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     */
    public function __construct(Client $client, string $fromNumber, string $statusCallback, EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->fromNumber = $fromNumber;
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->statusCallback = $statusCallback;
    }

    public function __invoke(Sms $sms)
    {
        $sms = $sms->getSms();

        $sender = $this->entityManager->getRepository(User::class)->find($sms->getSender()->getId());
        $sms->setSender($sender);

        try {
            $response = $this->client->messages->create($sms->getRecipient(), [
                'from' => $this->fromNumber,
                'body' => $sms->getBody(),
                'statusCallback' => $this->statusCallback
            ]);

            $status = $this->entityManager->getRepository(SmsStatus::class)->findOneBy(['name' => $response->status]);

            if (!$status) {
                $this->logger->error('SMS status missing', [$sms]);
                return false;
            }

            $sms->setStatus($status);
            $sms->setSid($response->sid);
            $this->entityManager->persist($sms);
            $this->entityManager->flush();
            return true;

        } catch (HandlerFailedException $exception) {
            $status = $this->entityManager->getRepository(SmsStatus::class)->findOneBy(['name' => SmsStatus::STATUS_FAILED]);
            $sms->setStatus($status);
            $this->entityManager->persist($sms);
            $this->entityManager->flush();
            $this->logger->error($exception);
        }
        return false;
    }
}