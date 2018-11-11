<?php

declare(strict_types=1);

namespace MajidMvulle\Bundle\StorageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class GalleryMedia.
 *
 * @author Majid Mvulle <majid@majidmvulle.com>
 *
 * @ORM\Entity(repositoryClass="MajidMvulle\Bundle\StorageBundle\Repository\GalleryMediaRepository")
 * @ORM\Table(name="majidmvulle_gallery_media")
 *
 * @Serializer\ExclusionPolicy("all")
 */
class GalleryMedia
{
    /**
     * @var Gallery
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="MajidMvulle\Bundle\StorageBundle\Entity\Gallery", inversedBy="galleryMedia")
     * @ORM\JoinColumn(name="gallery_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $gallery;

    /**
     * @var Media
     *
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="MajidMvulle\Bundle\StorageBundle\Entity\Media", inversedBy="galleryMedia")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     *
     * @Serializer\Expose()
     */
    protected $media;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="smallint")
     *
     * @Serializer\Expose()
     */
    protected $position;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_enabled", type="boolean", options={"default": true})
     */
    protected $enabled;

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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

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

    public function getGallery(): ?Gallery
    {
        return $this->gallery;
    }

    public function setGallery(?Gallery $gallery): self
    {
        $this->gallery = $gallery;

        return $this;
    }

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(?Media $media): self
    {
        $this->media = $media;

        return $this;
    }
}
