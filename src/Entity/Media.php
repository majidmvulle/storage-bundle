<?php

declare(strict_types=1);

namespace MajidMvulle\Bundle\StorageBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Media.
 *
 * @author Majid Mvulle <majid@majidmvulle.com>
 *
 * @ORM\Entity(repositoryClass="MajidMvulle\Bundle\StorageBundle\Repository\MediaRepository")
 * @ORM\Table(name="majidmvulle_media")
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Media
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
     * @ORM\Column(name="name", type="string", length=100)
     *
     * @Assert\NotBlank()
     *
     * @Serializer\Expose()
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="original_name", type="string", length=100)
     *
     * @Assert\NotBlank()
     *
     * @Serializer\Expose()
     */
    protected $originalName;

    /**
     * @var int
     *
     * @ORM\Column(name="width", type="integer")
     *
     * @Assert\NotBlank()
     *
     * @Serializer\Expose()
     */
    protected $width;

    /**
     * @var int
     *
     * @ORM\Column(name="height", type="integer")
     *
     * @Assert\NotBlank()
     *
     * @Serializer\Expose()
     */
    protected $height;

    /**
     * @var string
     *
     * @ORM\Column(name="content_type", type="string", length=50)
     * @Assert\NotBlank()
     *
     * @Serializer\Expose()
     */
    protected $contentType;

    /**
     * @var int
     *
     * @ORM\Column(name="content_size", type="integer")
     * @Assert\NotBlank()
     * @Assert\GreaterThan(0)
     *
     * @Serializer\Expose()
     */
    protected $contentSize;

    /**
     * @var string
     *
     * @ORM\Column(name="filesystem", type="string", length=100)
     */
    protected $filesystem;

    /**
     * @var string
     *
     * @ORM\Column(name="base_path", type="string", length=255)
     */
    protected $basePath;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    protected $path;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_local", type="boolean", options={"default": true})
     */
    protected $local;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_transportable", type="boolean", options={"default": false})
     */
    protected $transportable;

    /**
     * @var \DateTimeInterface
     *
     * @ORM\Column(name="transported_at", type="datetime", nullable=true)
     */
    protected $transportedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="checksum", type="string", length=40)
     *
     * @Serializer\Expose()
     */
    protected $checksum;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_enabled", type="boolean", options={"default": true})
     */
    protected $enabled;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_deleted", type="boolean", nullable=true)
     *
     * @Serializer\Expose()
     */
    protected $deleted;

    /**
     * @var GalleryMedia
     *
     * @ORM\OneToOne(targetEntity="MajidMvulle\Bundle\StorageBundle\Entity\GalleryMedia", mappedBy="media", orphanRemoval=true)
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
    protected $createdAt;

    /**
     * @var \DateTimeInterface
     *
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(name="updated_at", type="datetime")
     *
     * @Serializer\Expose()
     */
    protected $updatedAt;

    /**
     * @var string
     *
     * @Serializer\Expose()
     */
    protected $url;

    public function __construct()
    {
        $this->galleryMedia = new ArrayCollection();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
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

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(string $originalName): self
    {
        $this->originalName = $originalName;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    public function setContentType(string $contentType): self
    {
        $this->contentType = $contentType;

        return $this;
    }

    public function getContentSize(): ?int
    {
        return $this->contentSize;
    }

    public function setContentSize(int $contentSize): self
    {
        $this->contentSize = $contentSize;

        return $this;
    }

    public function getFilesystem(): ?string
    {
        return $this->filesystem;
    }

    public function setFilesystem(string $filesystem): self
    {
        $this->filesystem = $filesystem;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function isLocal(): ?bool
    {
        return $this->local;
    }

    public function getLocal(): ?bool
    {
        return $this->local;
    }

    public function setLocal(bool $local): self
    {
        $this->local = $local;

        return $this;
    }

    public function getTransportable(): ?bool
    {
        return $this->transportable;
    }

    public function setTransportable(bool $transportable): self
    {
        $this->transportable = $transportable;

        return $this;
    }

    public function getTransportedAt(): ?\DateTimeInterface
    {
        return $this->transportedAt;
    }

    public function setTransportedAt(?\DateTimeInterface $transportedAt): self
    {
        $this->transportedAt = $transportedAt;

        return $this;
    }

    public function getChecksum(): ?string
    {
        return $this->checksum;
    }

    public function setChecksum(string $checksum): self
    {
        $this->checksum = $checksum;

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

    public function getPathAndName()
    {
        return sprintf('%s/%s', $this->path, $this->name);
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getFullPath(): string
    {
        return sprintf('%s/%s', $this->basePath, $this->getPathAndName());
    }

    public function getBasePath(): ?string
    {
        return $this->basePath;
    }

    public function setBasePath(string $basePath): self
    {
        $this->basePath = $basePath;

        return $this;
    }

    public function getGalleryMedia(): ?GalleryMedia
    {
        return $this->galleryMedia;
    }

    public function setGalleryMedia(?GalleryMedia $galleryMedia): self
    {
        $this->galleryMedia = $galleryMedia;

        // set (or unset) the owning side of the relation if necessary
        $newMedia = null === $galleryMedia ? null : $this;

        if ($galleryMedia && $newMedia !== $galleryMedia->getMedia()) {
            $galleryMedia->setMedia($newMedia);
        }

        return $this;
    }

    public function getGallery()
    {
        return $this->galleryMedia->getGallery();
    }
}
