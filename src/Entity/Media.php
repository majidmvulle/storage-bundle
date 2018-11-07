<?php

declare(strict_types=1);

namespace MajidMvulle\Bundle\StorageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Media.
 *
 * @author Majid Mvulle <majid@majidmvulle.com>
 *
 * @ORM\Entity(repositoryClass="MajidMvulle\Bundle\StorageBundle\Bundle\Repository\MediaRepository")
 * @ORM\Table(name="majidmvulle_media")
 */
class Media
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     *
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="original_name", type="string", length=100)
     *
     * @Assert\NotBlank()
     */
    protected $originalName;

    /**
     * @var int
     *
     * @ORM\Column(name="width", type="integer")
     *
     * @Assert\NotBlank()
     */
    protected $width;

    /**
     * @var int
     *
     * @ORM\Column(name="height", type="integer")
     *
     * @Assert\NotBlank()
     */
    protected $height;

    /**
     * @var string
     *
     * @ORM\Column(name="content_type", type="string", length=50)
     * @Assert\NotBlank()
     */
    protected $contentType;

    /**
     * @var int
     *
     * @ORM\Column(name="content_size", type="integer")
     * @Assert\NotBlank()
     * @Assert\GreaterThan(0)
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
     */
    protected $checksum;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_enabled", type="boolean", options={"default": true})
     */
    protected $enabled;

    /**
     * @var GalleryMedia
     *
     * @ORM\OneToMany(targetEntity="MajidMvulle\Bundle\StorageBundle\Bundle\Entity\GalleryMedia", mappedBy="media")
     */
    protected $galleryMedia;

    /**
     * @var \DateTimeInterface
     *
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTimeInterface
     *
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;
}
