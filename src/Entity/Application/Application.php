<?php

namespace App\Entity\Application;

use App\Entity\Event;
use App\Repository\ApplicationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Application
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?Event $event = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $instagram = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pursuit = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $referral = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $website = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $experience = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $openResponse = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created = null;

    #[ORM\Column(length: 255)]
    private ?string $status = 'pending';

    public function __toString(): string
    {
        return $this->getName() ?? 'New applicant';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
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

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(?string $instagram): self
    {
        $this->instagram = $instagram;

        return $this;
    }

    public function getPursuit(): ?string
    {
        return $this->pursuit;
    }

    public function setPursuit(?string $pursuit): self
    {
        $this->pursuit = $pursuit;

        return $this;
    }

    public function getReferral(): ?string
    {
        return $this->referral;
    }

    public function setReferral(?string $referral): self
    {
        $this->referral = $referral;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getExperience(): ?string
    {
        return $this->experience;
    }

    public function setExperience(?string $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getOpenResponse(): ?string
    {
        return $this->openResponse;
    }

    public function setOpenResponse(?string $openResponse): self
    {
        $this->openResponse = $openResponse;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedToNow(): self
    {
        return $this->setCreated(new \DateTimeImmutable());
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(ApplicationStatus|string|null $status): self
    {
        if ($status instanceof ApplicationStatus) {
            $this->status = $status->value;
        } else {
            if (!in_array($status, array_column(ApplicationStatus::cases(), 'value'))) {
                throw new \ValueError(sprintf('Unknown status "%s"', $status));
            }

            $this->status = $status;
        }

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
