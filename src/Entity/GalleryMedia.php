<?php

declare(strict_types=1);

namespace MajidMvulle\Bundle\StorageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class GalleryMedia.
 *
 * @author Majid Mvulle <majid@majidmvulle.com>
 *
 * @ORM\Entity(repositoryClass="MajidMvulle\Bundle\StorageBundle\Bundle\Repository\GalleryMediaRepository")
 * @ORM\Table(name="majidmvulle_gallery_media")
 */
class GalleryMedia
{
    /**
     * @var Gallery
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="MajidMvulle\Bundle\StorageBundle\Bundle\Entity\Gallery", inversedBy="galleryMedia")
     * @ORM\JoinColumn(name="gallery_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $gallery;

    /**
     * @var Gallery
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="MajidMvulle\Bundle\StorageBundle\Bundle\Entity\Media", inversedBy="galleryMedia")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $media;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="smallint")
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
     */
    private $createdAt;

    /**
     * @var \DateTimeInterface
     *
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;
}
