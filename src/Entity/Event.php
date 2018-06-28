<?php

namespace App\Entity;

use JsonSerializable;
use DateTime;

class Event implements JsonSerializable {

    protected $id;
    protected $title;
    protected $date;
    protected $description;
    protected $meeting_location;
    protected $model_theme;
    protected $photographer_challenge;

    public function jsonSerialize() {
        return [
            'title' => $this->getTitle(),
            'date' => $this->getDate(),
            'description' => $this->getDescription(),
            'meeting_location' => $this->getMeetingLocation(),
            'model_theme' => $this->getModelTheme(),
            'photographer_challenge' => $this->getPhotographerChallenge(),
        ];
    }

    public function __toString() {
        return $this->getTitle() ?: 'New Event';
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getModelTheme(): ?string {
        return $this->model_theme;
    }

    public function setModelTheme(?string $theme): Event {
        $this->model_theme = $theme;
        return $this;
    }

    public function getPhotographerChallenge(): ?string {
        return $this->photographer_challenge;
    }

    public function setPhotographerChallenge(?string $challenge): Event {
        $this->photographer_challenge = $challenge;
        return $this;
    }

    public function getTitle(): ?string {
        return $this->title;
    }

    public function setTitle(?string $title): Event {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): Event {
        $this->description = $description;
        return $this;
    }

    public function getMeetingLocation(): ?string {
        return $this->meeting_location;
    }

    public function setMeetingLocation(?string $meeting_location): Event {
        $this->meeting_location = $meeting_location;
        return $this;
    }

    public function getDate(): ?DateTime {
        return $this->date;
    }

    public function setDate(?DateTime $date): Event {
        $this->date = $date;
        return $this;
    }
}
