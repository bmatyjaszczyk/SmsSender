<?php

namespace App\Controller;

use App\Entity\Sms;
use App\Entity\SmsStatus;
use App\Entity\User;
use App\Form\Sms as SmsForm;
use App\Repository\SmsRepository;
use App\SmsService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sms")
 */
class SmsController extends AbstractController
{
    /**
     * @var SmsService
     */
    private $smsService;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var CacheItemPoolInterface
     */
    private $cacheItemPool;

    /**
     * @var SmsRepository
     */
    private $smsRepository;

    public function __construct(
        SmsService $smsService,
        EntityManagerInterface $entityManager,
        CacheItemPoolInterface $cacheItemPool,
        SmsRepository $smsRepository
    )
    {
        $this->smsService = $smsService;
        $this->entityManager = $entityManager;
        $this->cacheItemPool = $cacheItemPool;
        $this->smsRepository = $smsRepository;
    }

    /**
     * @Route("/", name="sms_index", methods={"GET"})
     * @param SmsRepository $smsRepository
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('sms/index.html.twig', [
            'sms' => $this->smsRepository->findBy([], ['id' => 'desc']),
        ]);
    }

    /**
     * @Route("/new", name="sms_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function new(Request $request): Response
    {
        $user = $this->getUser();

        if ($this->isLocked($user)) {
            return $this->render('sms/locked.html.twig', []);
        }

        $sms = new Sms();
        $form = $this->createForm(SmsForm::class, $sms);
        $sms->setCreatedAt(new \DateTime('now', new \DateTimeZone('Europe/London')));
        $sms->setSender($user);
        $status = $this->entityManager->getRepository(SmsStatus::class)->findOneBy(['name' => SmsStatus::STATUS_SENDING]);
        $sms->setStatus($status);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->smsService->send($sms, $user);
            return $this->redirectToRoute('sms_index');
        }

        return $this->render('sms/new.html.twig', [
            'sms' => $sms,
            'form' => $form->createView(),
        ]);
    }

    private function isLocked(User $user): bool
    {
        $lock = $this->cacheItemPool->getItem('lock.' . $user->getId());
        return $lock->isHit();
    }
}
