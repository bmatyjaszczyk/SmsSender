<?php

namespace App\Entity;

use App\Repository\SmsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SmsRepository::class)
 */
class Sms
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", length=140)
     * @Assert\NotBlank
     * @Assert\NotNull
     */
    private $body;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sms")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     * @Assert\NotNull
     */
    private $sender;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=SmsStatus::class, inversedBy="sms")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Regex(
     *     pattern="/^[+][0-9]{1,3}[0-9]{5,13}$/",
     *     message="Please provide valid mobile number"
     * )
     */
    private $recipient;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getStatus(): ?SmsStatus
    {
        return $this->status;
    }

    public function setStatus(?SmsStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getRecipient(): ?string
    {
        return $this->recipient;
    }

    public function setRecipient(string $recipient): self
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function getSid(): ?string
    {
        return $this->sid;
    }

    public function setSid(string $sid): self
    {
        $this->sid = $sid;

        return $this;
    }
}
