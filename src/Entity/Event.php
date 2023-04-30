<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * Event
 */
#[ORM\Table(name: 'event')]
#[ORM\Entity]
class Event implements \JsonSerializable
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $id;

    /**
     * @var string
     */
    #[ORM\Column(name: 'title', type: 'string')]
    private $title;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'date', type: 'datetime')]
    private $date;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'description', type: 'text', nullable: true)]
    private $description;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'meeting_location', type: 'text', nullable: true)]
    private $meeting_location;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'meeting_instructions', type: 'text', nullable: true)]
    private $meeting_instructions;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'parking', type: 'text', nullable: true)]
    private $parking;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'model_theme', type: 'text', nullable: true)]
    private $model_theme;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'photographer_challenge', type: 'text', nullable: true)]
    private $photographer_challenge;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'facebook_link', type: 'string', nullable: true)]
    private $facebook_link;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'password', type: 'string', nullable: true)]
    private $password;

    /**
     * @var \App\Entity\Image
     */
    #[ORM\JoinColumn(name: 'banner_image_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Image')]
    private $banner_image;

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'title' => $this->getTitle(),
            'date' => $this->getDate(),
            'description' => $this->getDescription(),
            'meeting_location' => $this->getMeetingLocation(),
            'meeting_instructions' => $this->getMeetingInstructions(),
            'parking' => $this->getParking(),
            'model_theme' => $this->getModelTheme(),
            'photographer_challenge' => $this->getPhotographerChallenge(),
            'facebook_link' => $this->getFacebookLink(),
        ];
    }

    public function __toString()
    {
        return $this->getTitle() ?: 'New Event';
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): Event
    {
        $this->password = $password;
        return $this;
    }

    public function getBannerImageUrl(): ?string
    {
        return $this->getBannerImage() ? $this->getBannerImage()->getFilename() : '';
    }

    public function getBannerImage(): ?Image
    {
        return $this->banner_image;
    }

    public function setBannerImage(?Image $banner_image): Event
    {
        $this->banner_image = $banner_image;
        return $this;
    }

    public function getParking(): ?string
    {
        return $this->parking;
    }

    public function setParking(?string $parking): Event
    {
        $this->parking = $parking;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFacebookLink(): ?string
    {
        return $this->facebook_link;
    }

    public function setFacebookLink(?string $link): Event
    {
        $this->facebook_link = $link;
        return $this;
    }

    public function getModelTheme(): ?string
    {
        return $this->model_theme;
    }

    public function setModelTheme(?string $theme): Event
    {
        $this->model_theme = $theme;
        return $this;
    }

    public function getPhotographerChallenge(): ?string
    {
        return $this->photographer_challenge;
    }

    public function setPhotographerChallenge(?string $challenge): Event
    {
        $this->photographer_challenge = $challenge;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): Event
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): Event
    {
        $this->description = $description;
        return $this;
    }

    public function getMeetingLocation(): ?string
    {
        return $this->meeting_location;
    }

    public function setMeetingLocation(?string $meeting_location): Event
    {
        $this->meeting_location = $meeting_location;
        return $this;
    }

    public function getMeetingInstructions(): ?string
    {
        return $this->meeting_instructions;
    }

    public function setMeetingInstructions(?string $meeting_instructions): Event
    {
        $this->meeting_instructions = $meeting_instructions;
        return $this;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(?DateTime $date): Event
    {
        $this->date = $date;
        return $this;
    }
}
