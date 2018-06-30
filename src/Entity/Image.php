<?php

namespace App\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use JsonSerializable;
use DateTime;

class Image implements JsonSerializable {

    protected $id;
    protected $filename;
    protected $updated;

    // unmapped - used for temp upload
    protected $file;

    public function __toString() {
        return $this->getFilename() ?: 'New Image';
    }

    public function jsonSerialize() {
        return [
            'filename' => $this->getFilename(),
        ];
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getFile(): ?UploadedFile {
        return $this->file;
    }

    public function setFile(?UploadedFile $file) {
        $this->file = $file;
    }

    public function getFilename(): ?string {
        return $this->filename;
    }

    public function setFilename(?string $filename): Image {
        $this->filename = $filename;
        return $this;
    }

    public function getUpdated(): ?DateTime {
        return $this->updated;
    }

    public function setUpdated(?DateTime $updated): Image {
        $this->updated = $updated;
        return $this;
    }

    public function lifecycleFileUpload() {
        $this->setUpdated(new DateTime);
    }
}
