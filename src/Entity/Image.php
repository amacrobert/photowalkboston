<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Image implements JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string")
     */
    private $filename;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;

    // unmapped - used for temp upload
    protected ?UploadedFile $file;

    public function __toString()
    {
        return $this->getFilename() ?: 'New Image';
    }

    /**
     * @return null[]|string[]
     */
    public function jsonSerialize(): array
    {
        return [
            'filename' => $this->getFilename(),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    public function setFile(?UploadedFile $file): self
    {
        $this->file = $file;
        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;
        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(?DateTime $updated): self
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function lifecycleFileUpload(): void
    {
        $this->setUpdated(new DateTime);
    }
}
