<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Table(name: 'image')]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Image implements \JsonSerializable
{
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id;

    /**
     * @var string
     */
    #[ORM\Column(name: 'filename', type: 'string')]
    private $filename;

    #[ORM\Column(name: 'credit', type: 'string', nullable: true)]
    private $credit;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'updated', type: 'datetime')]
    private $updated;

    // unmapped - used for temp upload
    protected ?UploadedFile $file = null;

    public function __toString()
    {
        return $this->getFilename() ?: 'New Image';
    }

    /**
     * @return string[]
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

    public function setFilename(?string $filename): Image
    {
        $this->filename = $filename;
        return $this;
    }

    public function getCredit(): ?string
    {
        return $this->credit;
    }

    public function setCredit(?string $credit): self
    {
        $this->credit = $credit;
        return $this;
    }

    public function getUpdated(): ?DateTime
    {
        return $this->updated;
    }

    public function setUpdated(?DateTime $updated): Image
    {
        $this->updated = $updated;
        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function lifecycleFileUpload(): self
    {
        return $this->setUpdated(new DateTime());
    }
}
