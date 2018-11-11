<?php

declare(strict_types=1);

namespace MajidMvulle\Bundle\StorageBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Gallery.
 *
 * @author Majid Mvulle <majid@majidmvulle.com>
 *
 * @ORM\Entity(repositoryClass="MajidMvulle\Bundle\StorageBundle\Repository\GalleryRepository")
 * @ORM\Table(name="majidmvulle_gallery")
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Gallery
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id()
     * @ORM\Column(name="id", type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     *
     * @Serializer\Expose()
     * @Serializer\Type("string")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="related_entity", type="string", length=255)
     */
    protected $relatedEntity;

    /**
     * @var string
     *
     * @ORM\Column(name="related_entity_id", type="string", length=100)
     */
    protected $relatedEntityId;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_deleted", type="boolean", nullable=true)
     */
    protected $deleted;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_enabled", type="boolean", options={"default": true})
     */
    protected $enabled;

    /**
     * @var GalleryMedia
     *
     * @ORM\OneToMany(targetEntity="MajidMvulle\Bundle\StorageBundle\Entity\GalleryMedia", mappedBy="gallery")
     * @ORM\OrderBy({"position": "ASC"})
     *
     * @Serializer\Expose()
     */
    protected $galleryMedia;

    /**
     * @var \DateTimeInterface
     *
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @Serializer\Expose()
     */
    private $createdAt;

    /**
     * @var \DateTimeInterface
     *
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(name="updated_at", type="datetime")
     *
     * @Serializer\Expose()
     */
    private $updatedAt;

    public function __construct()
    {
        $this->enabled = true;
        $this->galleryMedia = new ArrayCollection();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getRelatedEntity(): ?string
    {
        return $this->relatedEntity;
    }

    public function setRelatedEntity(string $relatedEntity): self
    {
        $this->relatedEntity = $relatedEntity;

        return $this;
    }

    public function getRelatedEntityId(): ?string
    {
        return $this->relatedEntityId;
    }

    public function setRelatedEntityId(string $relatedEntityId): self
    {
        $this->relatedEntityId = $relatedEntityId;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|GalleryMedia[]
     */
    public function getGalleryMedia(): Collection
    {
        return $this->galleryMedia;
    }

    public function addGalleryMedia(GalleryMedia $galleryMedia): self
    {
        if (!$this->galleryMedia->contains($galleryMedia)) {
            $this->galleryMedia[] = $galleryMedia;
            $galleryMedia->setGallery($this);
        }

        return $this;
    }

    public function removeGalleryMedia(GalleryMedia $galleryMedia): self
    {
        if ($this->galleryMedia->contains($galleryMedia)) {
            $this->galleryMedia->removeElement($galleryMedia);
            // set the owning side to null (unless already changed)
            if ($galleryMedia->getGallery() === $this) {
                $galleryMedia->setGallery(null);
            }
        }

        return $this;
    }

    public function addGalleryMedium(GalleryMedia $galleryMedium): self
    {
        if (!$this->galleryMedia->contains($galleryMedium)) {
            $this->galleryMedia[] = $galleryMedium;
            $galleryMedium->setGallery($this);
        }

        return $this;
    }

    public function removeGalleryMedium(GalleryMedia $galleryMedium): self
    {
        if ($this->galleryMedia->contains($galleryMedium)) {
            $this->galleryMedia->removeElement($galleryMedium);
            // set the owning side to null (unless already changed)
            if ($galleryMedium->getGallery() === $this) {
                $galleryMedium->setGallery(null);
            }
        }

        return $this;
    }

    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(?bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }
}
