<?php

namespace App\DataFixtures;

use App\Entity\SmsStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SmsStatusFixtures extends Fixture
{
    protected $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        foreach (SmsStatus::STATUSES as $status) {
            $newStatus = new SmsStatus();
            $newStatus->setName($status);
            $manager->persist($newStatus);
        }

        $manager->flush();
    }
}
