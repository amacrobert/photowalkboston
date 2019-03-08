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
    protected $meeting_instructions;
    protected $parking;
    protected $model_theme;
    protected $photographer_challenge;
    protected $google_form_link;
    protected $facebook_link;
    protected $banner_image;
    protected $password;

    public function jsonSerialize() {
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

    public function __toString() {
        return $this->getTitle() ?: 'New Event';
    }

    public function getGoogleFormLink(): ?string {
        return $this->google_form_link;
    }

    public function getGoogleFormLinkEmbedded(): ?string {
        if (!($link = $this->getGoogleFormLink())) {
            return null;
        }

        list($url) = explode('?', $link);
        return $url .= '?embedded=true';
    }

    public function setGoogleFormLink(?string $google_form_link): Event {
        $this->google_form_link = $google_form_link;
        return $this;
    }

    public function getPassword(): ?string {
        return $this->password;
    }

    public function setPassword(?string $password): Event {
        $this->password = $password;
        return $this;
    }

    public function getBannerImageUrl(): ?string {
        return $this->getBannerImage() ? $this->getBannerImage()->getFilename() : '';
    }

    public function getBannerImage(): ?Image {
        return $this->banner_image;
    }

    public function setBannerImage(?Image $banner_image): Event {
        $this->banner_image = $banner_image;
        return $this;
    }

    public function getParking(): ?string {
        return $this->parking;
    }

    public function setParking(?string $parking): Event {
        $this->parking = $parking;
        return $this;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getFacebookLink(): ?string {
        return $this->facebook_link;
    }

    public function setFacebookLink(?string $link): Event {
        $this->facebook_link = $link;
        return $this;
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

    public function getMeetingInstructions(): ?string {
        return $this->meeting_instructions;
    }

    public function setMeetingInstructions(?string $meeting_instructions): Event {
        $this->meeting_instructions = $meeting_instructions;
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
