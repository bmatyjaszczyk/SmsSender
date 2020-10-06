<?php

namespace App\Entity;

use App\Repository\SmsStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SmsStatusRepository::class)
 */
class SmsStatus
{
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_QUEUED = 'queued';
    const STATUS_SENDING = 'sending';
    const STATUS_SENT = 'sent';
    const STATUS_FAILED = 'failed';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_UNDELIVERED = 'undelivered';

    const STATUSES = [
        self::STATUS_ACCEPTED,
        self::STATUS_QUEUED,
        self::STATUS_SENDING,
        self::STATUS_SENT,
        self::STATUS_FAILED,
        self::STATUS_DELIVERED,
        self::STATUS_UNDELIVERED,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Sms::class, mappedBy="status")
     */
    private $sms;

    public function __construct()
    {
        $this->sms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Sms[]
     */
    public function getSms(): Collection
    {
        return $this->sms;
    }

    public function addSms(Sms $sms): self
    {
        if (!$this->sms->contains($sms)) {
            $this->sms[] = $sms;
            $sms->setStatus($this);
        }

        return $this;
    }

    public function removeSms(Sms $sms): self
    {
        if ($this->sms->contains($sms)) {
            $this->sms->removeElement($sms);
            // set the owning side to null (unless already changed)
            if ($sms->getStatus() === $this) {
                $sms->setStatus(null);
            }
        }

        return $this;
    }
}
