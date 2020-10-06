<?php

namespace App\Controller;

use App\Entity\Sms;
use App\Entity\SmsStatus;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TwilioController extends AbstractController
{
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
    private $accountSid;

    /**
     * TwilioController constructor.
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $entityManager
     * @param string $accountSid
     */
    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager, string $accountSid)
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->accountSid = $accountSid;
    }

    /**
     * @Route("/statusCallback", name="sms_status_callback", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function handleStatusCallback(Request $request): Response
    {
        $messageSid = $request->get('MessageSid');
        $messageStatus = $request->get('MessageStatus');
        $accountSid = $request->get('AccountSid');

        if ($accountSid !== $this->accountSid) {
            $this->logger->error('Twilio status callback: account sid is not correct');

            return new Response('', 500);
        }

        $message = $this->entityManager->getRepository(Sms::class)->findOneBy(['sid' => $messageSid]);

        if ($message) {
            $status = $this->entityManager->getRepository(SmsStatus::class)->findOneBy(['name' => $messageStatus]);

            if ($status) {
                $message->setStatus($status);
                $this->entityManager->persist($message);
                $this->entityManager->flush();

                return new Response('ok');
            }

            $this->logger->error('Twilio status callback: SMS status not found');

            return new Response('', 500);
        }

        return new Response('ok');
    }
}