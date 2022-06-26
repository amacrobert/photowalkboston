<?php

namespace App\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use JsonSerializable;
use DateTime;

use Doctrine\ORM\Mapping as ORM;

/**
 * Image
 */
#[ORM\Table(name: 'image')]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Image
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
    #[ORM\Column(name: 'filename', type: 'string')]
    private $filename;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'updated', type: 'datetime')]
    private $updated;

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

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function lifecycleFileUpload()
    {
        $this->setUpdated(new DateTime);
    }
}
